<?php

namespace Methodz\Helpers\Models\Builder;

use Methodz\Helpers\Database\Query\QueryHandler;
use Methodz\Helpers\Database\Query\QuerySelect;
use Methodz\Helpers\File\File;
use Methodz\Helpers\Models\CommonEnumTrait;
use Methodz\Helpers\Models\CommonTrait;
use Methodz\Helpers\Models\Model;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Type\_DateTime;
use Methodz\Helpers\Type\_Float;
use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\_String;
use Methodz\Helpers\Type\Enum\_DateTimeFormatEnum;
use Methodz\Helpers\Type\Enum\_StringFormatEnum;
use function Methodz\Helpers\Type\_string;

class ModelsBuilder
{
	private string $namespace;
	private string $directory;
	private string $name;
	private string $table_name;
	/**
	 * @var ModelsFieldBuilder[]
	 */
	private array $fields = [];
	/**
	 * @var ModelsFieldEnumBuilder[]
	 */
	private array $enums = [];
	/**
	 * @var ModelsFieldLinkBuilder[]
	 */
	private array $fieldsOutgoingLinks = [];
	/**
	 * @var ModelsFieldLinkBuilder[]
	 */
	private array $fieldsIncomingLinks = [];

	private function __construct() { }

	public static function build(string $directory, string $database): void
	{
		$tablesResult = $database::executeRequest(
			QueryHandler::select("*")
				->from("`information_schema`.TABLES")
				->where("`TABLE_SCHEMA`=:table_schema")
				->addParameter('table_schema', $database::DB_NAME)
		);

		$models = [];

		if (!$tablesResult->isOK()) {
			throw $tablesResult->getError();
		}

		foreach ($tablesResult->getResult() as $tableArray) {
			$tableName = $tableArray['TABLE_NAME'];

			$model = new static();
			$model->directory = $directory . "/" . Tools::normaliseString($database::DB_NAME, _StringFormatEnum::CAMEL_CASE);
			$model->namespace = "Models\\" . Tools::normaliseString($database::DB_NAME, _StringFormatEnum::CAMEL_CASE);
			$model->name = Tools::normaliseString($tableName, _StringFormatEnum::CAMEL_CASE);
			$model->table_name = $tableName;

			$columnsResult = $database::executeRequest(
				QueryHandler::select("*")
					->from("`information_schema`.COLUMNS")
					->where("`TABLE_SCHEMA`=:table_schema AND `TABLE_NAME`=:table_name")
					->addParameter('table_schema', $database::DB_NAME)
					->addParameter('table_name', $tableName)
					->orderBY("ORDINAL_POSITION ASC")
			);

			foreach ($columnsResult->getResult() as $columnArray) {
				$columnName = $columnArray['COLUMN_NAME'];

				$outgoingLinksResult = $database::executeRequest(
					QueryHandler::select("*")
						->from("`information_schema`.KEY_COLUMN_USAGE")
						->where("`TABLE_SCHEMA`=:table_schema AND `TABLE_NAME`=:table_name AND `CONSTRAINT_NAME` NOT LIKE 'PRIMARY' AND `COLUMN_NAME`=:column_name AND `REFERENCED_TABLE_SCHEMA` IS NOT NULL AND `REFERENCED_TABLE_NAME` IS NOT NULL AND `REFERENCED_COLUMN_NAME` IS NOT NULL")
						->addParameter('table_schema', $database::DB_NAME)
						->addParameter('table_name', $tableName)
						->addParameter('column_name', $columnName)
				);
				$incomingLinksResult = $database::executeRequest(
					QueryHandler::select("*")
						->from("`information_schema`.KEY_COLUMN_USAGE")
						->where("`REFERENCED_TABLE_SCHEMA`=:table_schema AND `REFERENCED_TABLE_NAME`=:table_name AND `CONSTRAINT_NAME` NOT LIKE 'PRIMARY' AND `REFERENCED_COLUMN_NAME`=:column_name")
						->addParameter('table_schema', $database::DB_NAME)
						->addParameter('table_name', $tableName)
						->addParameter('column_name', $columnName)
				);

				$association = [
					"tinyint" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"smallint" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"mediumint" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"int" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"bigint" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"decimal" => ['class' => "\\" . _Float::class, 'function' => "_float"],
					"float" => ['class' => "\\" . _Float::class, 'function' => "_float"],
					"double" => ['class' => "\\" . _Float::class, 'function' => "_float"],
					"bit" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"date" => ['class' => "\\" . _DateTime::class, 'function' => "_date"],
					"datetime" => ['class' => "\\" . _DateTime::class, 'function' => "_datetime"],
					"timestamp" => ['class' => "\\" . _DateTime::class, 'function' => "_datetime"],
					"time" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"year" => ['class' => "\\" . _Int::class, 'function' => "_int"],
					"char" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"varchar" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"tinytext" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"text" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"mediumtext" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"longtext" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"binary" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"varbinary" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"tinyblob" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"blob" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"mediumblob" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"longblob" => ['class' => "\\" . _String::class, 'function' => "_string"],
					"json" => ['class' => "\\" . _String::class, 'function' => "_string"],
				];


				$field = ModelsFieldBuilder::init(
					name: Tools::normaliseString($columnName, _StringFormatEnum::SNAKE_CASE),
					nullable: $columnArray['IS_NULLABLE'] !== "NO"
				);

				if (array_key_exists($columnArray['DATA_TYPE'], $association)) {
					$field->setType($association[$columnArray['DATA_TYPE']]);
				} else {
					$type = $model->namespace . "\\Enum";

					$values = explode(',', str_replace("$type(", '', str_replace(')', '', str_replace("'", '', $columnArray['COLUMN_TYPE']))));
					$values[0] = str_replace(['set(', 'enum('], '', $values[0]);

					$enum = ModelsFieldEnumBuilder::init(
						name: $model->name . Tools::normaliseString($columnName, _StringFormatEnum::CAMEL_CASE) . "Enum",
						namespace: $type,
						values: $values
					);

					$model->enums[] = $enum;

					$field->setType("\\" . $type . "\\" . $enum->getName());
				}


				if ($columnArray['COLUMN_DEFAULT'] !== null || $columnArray['IS_NULLABLE'] !== "NO") {
					$field->setDefaultValue($columnArray['COLUMN_DEFAULT']);
				}

				$model->fields[$columnName] = $field;

				foreach ($outgoingLinksResult->getResult() as $link) {
					$l = ModelsFieldLinkBuilder::init(
						target_type: "\\Models\\" . Tools::normaliseString($link['REFERENCED_TABLE_SCHEMA'], _StringFormatEnum::CAMEL_CASE) . "\\" . Tools::normaliseString($link['REFERENCED_TABLE_NAME'], _StringFormatEnum::CAMEL_CASE),
						target_field: Tools::normaliseString($link['REFERENCED_COLUMN_NAME'], _StringFormatEnum::SNAKE_CASE),
						name: Tools::normaliseString($link['REFERENCED_TABLE_NAME'], _StringFormatEnum::SNAKE_CASE),
						source_field: $field->getName(),
						nullable: $field->isNullable()
					);
					$model->fieldsOutgoingLinks[] = $l;
				}

				foreach ($incomingLinksResult->getResult() as $link) {
					$l = ModelsFieldLinkBuilder::init(
						target_type: "\\Models\\" . Tools::normaliseString($link['TABLE_SCHEMA'], _StringFormatEnum::CAMEL_CASE) . "\\" . Tools::normaliseString($link['TABLE_NAME'], _StringFormatEnum::CAMEL_CASE),
						target_field: $field->getName(),
						name: Tools::normaliseString($link['TABLE_NAME'], _StringFormatEnum::SNAKE_CASE) . "_list",
						source_field: Tools::normaliseString($link['COLUMN_NAME'], _StringFormatEnum::SNAKE_CASE),
						nullable: $field->isNullable()
					);
					$model->fieldsIncomingLinks[] = $l;
				}

			}

			$models[] = $model;

			break;
		}

		foreach ($models as $model) {
			$content = "<?php\n";
			$content .= "\n";
			$content .= "namespace " . $model->namespace . ";\n";
			$content .= "\n";
			$content .= "class " . $model->name . " extends \\" . Model::class . "\n";
			$content .= "{\n";
			$content .= "\n";
			$content .= "	use \\" . CommonTrait::class . ";\n";
			$content .= "\n";
			$content .= "	const _DATABASE = \\" . $database . "::class;\n";
			$content .= "	const _TABLE = \"" . $model->table_name . "\";\n";

			foreach (array_keys($model->fields) as $field) {
				$content .= "	const _" . strtoupper(ltrim($field, "_")) . " = \"$field\";\n";
			}

			$content .= "\n";

			foreach ($model->fields as $field) {
				if ($field->getName() !== "id") {
					$content .= "	private " . ($field->isNullable() ? "?" : "") . $field->getType() . " $" . $field->getName() . ";\n";
				}
			}

			if (count($model->fieldsOutgoingLinks) > 0) {
				$content .= "\n";

				foreach ($model->fieldsOutgoingLinks as $fieldsIncomingLink) {
					$content .= "	private ?" . $fieldsIncomingLink->getTargetType() . " $" . $fieldsIncomingLink->getName() . " = null;\n";
				}
			}

			if (count($model->fieldsIncomingLinks) > 0) {
				$content .= "\n";

				foreach ($model->fieldsIncomingLinks as $fieldsIncomingLink) {
					$content .= "	/**\n";
					$content .= "	 * @var " . $fieldsIncomingLink->getTargetType() . "[]|null\n";
					$content .= "	 */\n";
					$content .= "	private ?array $" . $fieldsIncomingLink->getName() . " = null;\n";
				}
			}

			$content .= "\n";
			$content .= "\n";
			$content .= "	private function __construct() { }\n";
			$content .= "\n";
			$content .= "\n";

			foreach ($model->fields as $field) {
				if ($field->getType() === "bool") {
					$content .= "	public function is" . Tools::normaliseString($field->getName(), _StringFormatEnum::CAMEL_CASE) . "(): " . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . "bool\n";
					$content .= "	{\n";
					$content .= "		return \$this->" . $field->getName() . ";\n";
					$content .= "	}\n";
					$content .= "\n";
				}
				$content .= "	public function get" . Tools::normaliseString($field->getName(), _StringFormatEnum::CAMEL_CASE) . "(): " . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . $field->getType() . "\n";
				$content .= "	{\n";
				$content .= "		return \$this->" . $field->getName() . ";\n";
				$content .= "	}\n";
				$content .= "\n";
				$content .= "	public function set" . Tools::normaliseString($field->getName(), _StringFormatEnum::CAMEL_CASE) . "(" . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . $field->getType() . " $" . $field->getName() . "): static\n";
				$content .= "	{\n";
				$content .= "		\$this->" . $field->getName() . " = $" . $field->getName() . ";\n";
				$content .= "\n";
				$content .= "		return \$this;\n";
				$content .= "	}\n";
				$content .= "\n";
			}

			foreach ($model->fieldsOutgoingLinks as $fieldsOutgoingLink) {
				$content .= "	public function get" . Tools::normaliseString($fieldsOutgoingLink->getName(), _StringFormatEnum::CAMEL_CASE) . "(): " . ($fieldsOutgoingLink->isNullable() ? "?" : "") . $fieldsOutgoingLink->getTargetType() . "\n";
				$content .= "	{\n";
				$content .= "		if (\$this->" . $fieldsOutgoingLink->getName() . " === null" . ($fieldsOutgoingLink->isNullable() ? " && \$this->" . $fieldsOutgoingLink->getSourceField() . " !== null" : "") . ") {\n";
				$content .= "			\$this->" . $fieldsOutgoingLink->getName() . " = " . $fieldsOutgoingLink->getTargetType() . "::findBy(" . $fieldsOutgoingLink->getTargetType() . "::_" . strtoupper($fieldsOutgoingLink->getTargetField()) . ", \$this->" . $fieldsOutgoingLink->getSourceField() . ");\n";
				$content .= "		}";
				$content .= "\n";
				$content .= "		return \$this->" . $fieldsOutgoingLink->getName() . ";\n";
				$content .= "	}\n";
			}

			foreach ($model->fieldsIncomingLinks as $fieldsIncomingLink) {
				$content .= "	/**\n";
				$content .= "	 * @return " . $fieldsIncomingLink->getTargetType() . "[]\n";
				$content .= "	 */\n";
				$content .= "	public function get" . Tools::normaliseString($fieldsIncomingLink->getName(), _StringFormatEnum::CAMEL_CASE) . "(): array\n";
				$content .= "	{\n";
				$content .= "		if (\$this->" . $fieldsIncomingLink->getName() . " === null) {\n";
				$content .= "			\$this->" . $fieldsIncomingLink->getName() . " = " . $fieldsIncomingLink->getTargetType() . "::findAllBy(" . $fieldsIncomingLink->getTargetType() . "::_" . strtoupper($fieldsIncomingLink->getSourceField()) . ", \$this->" . $fieldsIncomingLink->getTargetField() . ");\n";
				$content .= "		}";
				$content .= "\n";
				$content .= "		return \$this->" . $fieldsIncomingLink->getName() . ";\n";
				$content .= "	}\n";
			}

			$content .= "\n";
			$content .= "	public function save(?array \$data = null): static\n";
			$content .= "	{\n";
			$content .= "		return parent::save(\$data ?? [\n";
			foreach ($model->fields as $field) {
				if ($field->getName() !== "id") {
					$content .= "				static::_" . strtoupper($field->getName()) . " => \$this->" . $field->getName() . ($field->isEnum() ? "->value" : "->getValue()") . ",\n";
				}
			}
			$content .= "			]);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "\n";
			$initParametersArray = [];

			$fields = $model->fields;

			usort($fields, function ($a, $b) {
				$aC = $a->isNullable() || $a->haveDefaultValue();
				$bC = $b->isNullable() || $b->haveDefaultValue();

				if (!$aC && $bC) {
					return -1;
				} elseif ($aC && !$bC) {
					return 1;
				} else {
					return 0;
				}
			});

			foreach ($fields as $field) {
				if ($field->getName() !== "id") {
					$initParametersArray[] = ($field->isNullable() || $field->haveDefaultValue() ? "?" : "") . $field->getType() . " $" . $field->getName() . ($field->haveDefaultValue() ? " = null" : "");
				}
			}

			$content .= "	public static function init(" . implode(', ', $initParametersArray) . ", ?\\" . _Int::class . " \$id = null): static\n";
			$content .= "	{\n";
			$content .= "		\$_object = new static();\n";
			$content .= "\n";
			foreach ($fields as $field) {
				$format = null;
				$function = null;

				if ($field->isEnum()) {
					if ($field->haveDefaultValue() && $field->getDefaultValue() !== "null") {
						$function = $field->getType() . "::" . strtoupper(Tools::normaliseString($field->getDefaultValue(), _StringFormatEnum::SNAKE_CASE));
					}
				} else {
					if (_string($field->getFunctionType())->startsWith("_date")) {
						$format = match ($field->getFunctionType()) {
							"_date" => "\\" . _DateTimeFormatEnum::class . "::DATE",
							"_datetime" => "\\" . _DateTimeFormatEnum::class . "::DATETIME",
						};
					}
					if ($field->haveDefaultValue() && $field->getDefaultValue() !== "null") {
						if (_string($field->getFunctionType())->startsWith("_date")) {
							$function = "_datetime(\"" . $field->getDefaultValue() . "\", $format)";
						} elseif ($field->getFunctionType() === "_string") {
							$function = "_string(\"" . $field->getDefaultValue() . "\")";
						} else {
							$function = $field->getFunctionType() . "(" . $field->getDefaultValue() . ")";
						}
						$function = "\\Methodz\\Helpers\\Type\\$function";
					}
				}

				$content .= "		\$_object->" . $field->getName() . " = $" . $field->getName() . ($function !== null ? " ?? $function" : "") . ";\n";
			}
			$content .= "\n";
			$content .= "		return \$_object;\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	public static function fromArray(array \$data): static\n";
			$content .= "	{\n";
			$content .= "		return static::init(\n";
			foreach ($fields as $field) {
				if ($field->getName() !== "id") {
					if ($field->isEnum()) {
						$content .= "			" . $field->getName() . ": " . $field->getType() . "::" . ($field->isNullable() ? "tryFrom" : "from") . "(\$data[static::_" . strtoupper($field->getName()) . "]),\n";
					} else {
						$format = null;
						if (_string($field->getFunctionType())->startsWith("_date")) {
							$format = match ($field->getFunctionType()) {
								"_date" => "\\" . _DateTimeFormatEnum::class . "::DATE",
								"_datetime" => "\\" . _DateTimeFormatEnum::class . "::DATETIME",
							};
							$field->setFunctionType("_datetime");
						}

						if ($field->isNullable()) {
							$content .= "			" . $field->getName() . ": " . "array_key_exists(static::_" . strtoupper($field->getName()) . ", \$data) ? \\Methodz\\Helpers\\Type\\" . $field->getFunctionType() . "(\$data[static::_" . strtoupper($field->getName()) . "]" . ($format !== null ? ", $format" : "") . ") : null,\n";
						} else {
							$content .= "			" . $field->getName() . ": \\Methodz\\Helpers\\Type\\" . $field->getFunctionType() . "(\$data[static::_" . strtoupper($field->getName()) . "]" . ($format !== null ? ", $format" : "") . "),\n";
						}
					}
				}
			}
			$content .= "			id: \$data[static::_ID] ?? null,\n";
			$content .= "		)->set_data(\$data);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "\n";
			$content .= "	public static function findById(\\" . _Int::class . " \$id): ?static\n";
			$content .= "	{\n";
			$content .= "		return parent::findById(\$id);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	/**\n";
			$content .= "	 * @return static[]|null\n";
			$content .= "	 */\n";
			$content .= "	public static function findAll(bool \$idAsKey = false): ?array\n";
			$content .= "	{\n";
			$content .= "		return parent::findAll(\$idAsKey);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	public static function findByQuery(\\" . QuerySelect::class . " \$query): ?static\n";
			$content .= "	{\n";
			$content .= "		return parent::findByQuery(\$query);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	/**\n";
			$content .= "	 * @return static[]|null\n";
			$content .= "	 */\n";
			$content .= "	public static function findAllByQuery(\\" . QuerySelect::class . " \$query): ?array\n";
			$content .= "	{\n";
			$content .= "		return parent::findAllByQuery(\$query);\n";
			$content .= "	}\n";


			File::put($model->directory, $model->name . ".php", $content . "}\n");

			foreach ($model->enums as $enum) {
				$contentEnum = "<?php\n";
				$contentEnum .= "\n";
				$contentEnum .= "namespace " . $enum->getNamespace() . ";\n";
				$contentEnum .= "\n";
				$contentEnum .= "enum " . $enum->getName() . ": string\n";
				$contentEnum .= "{\n";
				$contentEnum .= "	use \\" . CommonEnumTrait::class . ";\n";
				$contentEnum .= "\n";
				foreach ($enum->getValues() as $value) {
					$contentEnum .= "	case " . strtoupper(Tools::normaliseString($value, _StringFormatEnum::SNAKE_CASE)) . " = \"$value\";\n";
				}
				$contentEnum .= "\n";
				$contentEnum .= "	public function toString(): string\n";
				$contentEnum .= "	{\n";
				$contentEnum .= "		return \$this->value;\n";
				$contentEnum .= "	}\n";

				File::put($model->directory . "/Enum", $enum->getName() . ".php", $contentEnum . "}\n");
			}
		}

	}
}
