<?php

namespace Methodz\Helpers\Models\Builder;

use Methodz\Helpers\Database\HelpersDatabase;
use Methodz\Helpers\Database\Query\QueryHandler;
use Methodz\Helpers\Database\Query\QuerySelect;
use Methodz\Helpers\Date\DateTime;
use Methodz\Helpers\File\File;
use Methodz\Helpers\Models\CommonEnumTrait;
use Methodz\Helpers\Models\CommonTrait;
use Methodz\Helpers\Models\Model;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;

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
		$tablesResult = HelpersDatabase::executeRequest(
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

			$model = new self();
			$model->directory = $directory . "/" . Tools::normaliseString($database::DB_NAME, ToolsNormaliseStringTypeEnum::CAMEL_CASE);
			$model->namespace = "Models\\" . Tools::normaliseString($database::DB_NAME, ToolsNormaliseStringTypeEnum::CAMEL_CASE);
			$model->name = Tools::normaliseString($tableName, ToolsNormaliseStringTypeEnum::CAMEL_CASE);
			$model->table_name = $tableName;

			$columnsResult = HelpersDatabase::executeRequest(
				QueryHandler::select("*")
					->from("`information_schema`.COLUMNS")
					->where("`TABLE_SCHEMA`=:table_schema AND `TABLE_NAME`=:table_name")
					->addParameter('table_schema', $database::DB_NAME)
					->addParameter('table_name', $tableName)
					->orderBY("ORDINAL_POSITION ASC")
			);

			foreach ($columnsResult->getResult() as $columnArray) {
				$columnName = $columnArray['COLUMN_NAME'];

				$outgoingLinksResult = HelpersDatabase::executeRequest(
					QueryHandler::select("*")
						->from("`information_schema`.KEY_COLUMN_USAGE")
						->where("`TABLE_SCHEMA`=:table_schema AND `TABLE_NAME`=:table_name AND `CONSTRAINT_NAME` NOT LIKE 'PRIMARY' AND `COLUMN_NAME`=:column_name AND `REFERENCED_TABLE_SCHEMA` IS NOT NULL AND `REFERENCED_TABLE_NAME` IS NOT NULL AND `REFERENCED_COLUMN_NAME` IS NOT NULL")
						->addParameter('table_schema', $database::DB_NAME)
						->addParameter('table_name', $tableName)
						->addParameter('column_name', $columnName)
				);
				$incomingLinksResult = HelpersDatabase::executeRequest(
					QueryHandler::select("*")
						->from("`information_schema`.KEY_COLUMN_USAGE")
						->where("`REFERENCED_TABLE_SCHEMA`=:table_schema AND `REFERENCED_TABLE_NAME`=:table_name AND `CONSTRAINT_NAME` NOT LIKE 'PRIMARY' AND `REFERENCED_COLUMN_NAME`=:column_name")
						->addParameter('table_schema', $database::DB_NAME)
						->addParameter('table_name', $tableName)
						->addParameter('column_name', $columnName)
				);

				$association = [
					"tinyint" => "int",
					"smallint" => "int",
					"mediumint" => "int",
					"int" => "int",
					"bigint" => "int",
					"decimal" => "float",
					"float" => "float",
					"double" => "float",
					"bit" => "int",
					"date" => "\\" . DateTime::class,
					"datetime" => "\\" . DateTime::class,
					"timestamp" => "\\" . DateTime::class,
					"time" => "string",
					"year" => "int",
					"char" => "string",
					"varchar" => "string",
					"tinytext" => "string",
					"text" => "string",
					"mediumtext" => "string",
					"longtext" => "string",
					"binary" => "string",
					"varbinary" => "string",
					"tinyblob" => "string",
					"blob" => "string",
					"mediumblob" => "string",
					"longblob" => "string",
					"json" => "string",
				];

				if (array_key_exists($columnArray['DATA_TYPE'], $association)) {
					$type = $association[$columnArray['DATA_TYPE']];
				} else {
					$type = $model->namespace . "\\Enum";

					$values = explode(',', str_replace("$type(", '', str_replace(')', '', str_replace("'", '', $columnArray['COLUMN_TYPE']))));
					$values[0] = str_replace(['set(', 'enum('], '', $values[0]);

					$enum = ModelsFieldEnumBuilder::init(
						name: $model->name . Tools::normaliseString($columnName, ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "Enum",
						namespace: $type,
						values: $values
					);

					$model->enums[] = $enum;

					$type = "\\" . $type . "\\" . $enum->getName();
				}

				$field = ModelsFieldBuilder::init(
					type: $type,
					name: Tools::normaliseString($columnName, ToolsNormaliseStringTypeEnum::SNAKE_CASE),
					nullable: $columnArray['IS_NULLABLE'] !== "NO"
				);

				$model->fields[$columnName] = $field;

				foreach ($outgoingLinksResult->getResult() as $link) {
					$l = ModelsFieldLinkBuilder::init(
						target_type: "\\Models\\" . Tools::normaliseString($link['REFERENCED_TABLE_SCHEMA'], ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "\\" . Tools::normaliseString($link['REFERENCED_TABLE_NAME'], ToolsNormaliseStringTypeEnum::CAMEL_CASE),
						target_field: Tools::normaliseString($link['REFERENCED_COLUMN_NAME'], ToolsNormaliseStringTypeEnum::SNAKE_CASE),
						name: Tools::normaliseString($link['REFERENCED_TABLE_NAME'], ToolsNormaliseStringTypeEnum::SNAKE_CASE),
						source_field: $field->getName(),
						nullable: $field->isNullable()
					);
					$model->fieldsOutgoingLinks[] = $l;
				}

				foreach ($incomingLinksResult->getResult() as $link) {
					$l = ModelsFieldLinkBuilder::init(
						target_type: "\\Models\\" . Tools::normaliseString($link['TABLE_SCHEMA'], ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "\\" . Tools::normaliseString($link['TABLE_NAME'], ToolsNormaliseStringTypeEnum::CAMEL_CASE),
						target_field: $field->getName(),
						name: Tools::normaliseString($link['TABLE_NAME'], ToolsNormaliseStringTypeEnum::SNAKE_CASE) . "_list",
						source_field: Tools::normaliseString($link['COLUMN_NAME'], ToolsNormaliseStringTypeEnum::SNAKE_CASE),
						nullable: $field->isNullable()
					);
					$model->fieldsIncomingLinks[] = $l;
				}

			}

			$models[] = $model;
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
				$content .= "	const _" . strtoupper($field) . " = \"$field\";\n";
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
					$content .= "	/**";
					$content .= "	 * @var " . $fieldsIncomingLink->getTargetType() . "[]|null";
					$content .= "	 */";
					$content .= "	private ?array $" . $fieldsIncomingLink->getName() . " = null;\n";
				}
			}

			$content .= "\n";
			$content .= "	private function __construct() { }\n";
			$content .= "\n";

			foreach ($model->fields as $field) {
				if ($field->getType() === "bool") {
					$content .= "	public function is" . Tools::normaliseString($field->getName(), ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "(): " . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . "bool\n";
					$content .= "	{\n";
					$content .= "		return \$this->" . $field->getName() . ";\n";
					$content .= "	}\n";
					$content .= "\n";
				}
				$content .= "	public function get" . Tools::normaliseString($field->getName(), ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "(): " . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . $field->getType() . "\n";
				$content .= "	{\n";
				$content .= "		return \$this->" . $field->getName() . ";\n";
				$content .= "	}\n";
				$content .= "\n";
				$content .= "	public function set" . Tools::normaliseString($field->getName(), ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "(" . ($field->isNullable() || $field->getName() === "id" ? "?" : "") . $field->getType() . " $" . $field->getName() . "): static\n";
				$content .= "	{\n";
				$content .= "		\$this->" . $field->getName() . " = $" . $field->getName() . ";\n";
				$content .= "\n";
				$content .= "		return \$this;\n";
				$content .= "	}\n";
				$content .= "\n";
			}

			foreach ($model->fieldsOutgoingLinks as $fieldsOutgoingLink) {
				$content .= "	public function get" . Tools::normaliseString($fieldsOutgoingLink->getName(), ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "(): " . ($fieldsOutgoingLink->isNullable() ? "?" : "") . $fieldsOutgoingLink->getTargetType() . "\n";
				$content .= "	{\n";
				$content .= "		if (\$this->" . $fieldsOutgoingLink->getName() . " === null" . ($fieldsOutgoingLink->isNullable() ? " && \$this->" . $fieldsOutgoingLink->getSourceField() . " !== null" : "") . ") {\n";
				$content .= "			\$this->" . $fieldsOutgoingLink->getName() . " = " . $fieldsOutgoingLink->getTargetType() . "::findBy(" . $fieldsOutgoingLink->getTargetType() . "::_" . strtoupper($fieldsOutgoingLink->getTargetField()) . ", \$this->" . $fieldsOutgoingLink->getSourceField() . ");\n";
				$content .= "		}";
				$content .= "\n";
				$content .= "		return \$this->" . $fieldsOutgoingLink->getName() . ";\n";
				$content .= "	}\n";
			}

			foreach ($model->fieldsIncomingLinks as $fieldsIncomingLink) {
				$content .= "	/**";
				$content .= "	 * @return " . $fieldsIncomingLink->getTargetType() . "[]";
				$content .= "	 */";
				$content .= "	public function get" . Tools::normaliseString($fieldsIncomingLink->getName(), ToolsNormaliseStringTypeEnum::CAMEL_CASE) . "(): array\n";
				$content .= "	{\n";
				$content .= "		if (\$this->" . $fieldsIncomingLink->getName() . " === null) {\n";
				$content .= "			\$this->" . $fieldsIncomingLink->getName() . " = " . $fieldsIncomingLink->getTargetType() . "::findAllBy(" . $fieldsIncomingLink->getTargetType() . "::_" . strtoupper($fieldsIncomingLink->getTargetField()) . ", \$this->" . $fieldsIncomingLink->getSourceField() . ");\n";
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
					$content .= "				self::_" . strtoupper($field->getName()) . " => \$this->" . $field->getName() . ($field->isEnum() ? "->value" : "") . ",\n";
				}
			}
			$content .= "			]);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "\n";
			$initParametersArray = [];

			foreach ($model->fields as $field) {
				if ($field->getName() !== "id") {
					$initParametersArray[] = ($field->isNullable() ? "?" : "") . $field->getType() . " $" . $field->getName();
				}
			}

			$content .= "	public static function init(" . implode(', ', $initParametersArray) . ", ?int \$id = null): static\n";
			$content .= "	{\n";
			$content .= "		\$_object = new self();\n";
			$content .= "\n";
			foreach ($model->fields as $field) {
				$content .= "		\$_object->" . $field->getName() . " = $" . $field->getName() . ";\n";
			}
			$content .= "\n";
			$content .= "		return \$_object;\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	public static function fromArray(array \$data): static\n";
			$content .= "	{\n";
			$content .= "		return static::init(\n";
			foreach ($model->fields as $field) {
				$content .= "			" . $field->getName() . ": " . ($field->isEnum() ? $field->getType() . "::" . ($field->isNullable() ? "tryFrom" : "from") : "") . "\$data[static::_" . strtoupper($field->getName()) . "]" . ($field->isNullable() && !$field->isEnum() ? " ?? null" : "") . ",\n";
			}
			$content .= "			id: \$data[static::_ID] ?? null,\n";
			$content .= "		);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "\n";
			$content .= "	public static function findById(int \$id): ?static\n";
			$content .= "	{\n";
			$content .= "		return parent::findById(\$id);\n";
			$content .= "	}\n";
			$content .= "\n";
			$content .= "	/**\n";
			$content .= "	 * @return self[]|null\n";
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
			$content .= "	 * @return self[]|null\n";
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
					$contentEnum .= "	case " . strtoupper(Tools::normaliseString($value, ToolsNormaliseStringTypeEnum::SNAKE_CASE)) . " = \"$value\";\n";
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
