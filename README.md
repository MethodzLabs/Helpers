# Helpers

**_php 8.1_** 

Menu:
- Cookie <a href="#cookie">#</a>
- Csv <a href="#csv">#</a>
- Curl <a href="#curl">#</a>
- Datetime <a href="#datetime">#</a>
- Directory <a href="#directory">#</a>
- File <a href="#file">#</a>
- Mail <a href="#mail">#</a>
- Url <a href="#url">#</a>
- UUID <a href="#uuid">#</a>

> ### <span id="cookie">**Cookie**</span>
> ```php
> namespace Methodz\Helpers\Accessors;
> 
> class Cookie
> {
> 	private string $name;
> 	private int $maxTime = 0;
> 	private string $domain = "";
> 	
> 	public function setDatetimeExpire(DateTime $dateTime): self;
> 	public function setTimeExpire(int $time): self;
> 	public function setDomain(string $domain): self;
> 	public function setExpireInTimePlusSeconds(int $seconds): self;
> 	public function save(mixed $value): bool;
> 	public function delete(): bool;
> 	
> 	public static function create(string $name): self;
> 	public static function get(string $name): mixed;
> 	public static function exist(string $name): bool;
> }
> ```

> ### <span id="csv">**Csv**</span>
> ```php
> namespace Methodz\Helpers\Csv;
> 
> class Csv
> {
> 	private array $data;
> 	private ?string $path;
> 	private ?string $fileName;
> 
> 	public function getData(): array;
> 	public function setData(array $data): self;
> 	public function save(?string $path = null, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): bool;
> 
> 	public static function fromFile(string $path, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): self;
> 	public static function fromString(string $csvString, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): self;
> 	public static function fromArray(array $csvData): self;
> }
> ```

> ### <span id="curl">**Curl**</span>
> ```php
> namespace Methodz\Helpers\Curl;
> 
> class Curl
> {
> 	private array $data;
> 	private ?string $path;
> 	private ?string $fileName;
> 
> 	public function setHeader(array $header): self;
> 	public function addHeader(CurlCommonHeaderKeyEnum|string $key, mixed $value): self;
> 	public function setHtaccessUsernameAndPassword(string $username, string $password): self;
> 	public function setRequestAsPost(bool $bool = true): self;
> 	public function setPOSTParameters(array $data): self;
> 	public function addPOSTParameters(string $key, mixed $value): self;
> 	public function setGETParameters(array $data): self;
> 	public function addGETParameters(string $key, mixed $value): self;
> 	public function setOptions(array $options): self;
> 	public function addOption(int $key, mixed $value): self;
> 	public function exec(bool $closeAfterExec = true): self;
> 	public function getInfos(): array;
> 	public function getInfo(CurlInfoKeyEnum $key): string|null;
> 	public function getResult(): string;
> 	public function getErrorString(): string;
> 	public function getError(): CurlErrorEnum;
> 	public function getErrorNumber(): int;
>  	public function close(): self;
> 
> 	public static function init(string $url): self;
> }
> ```

> ### <span id="datetime">**Datetime**</span>
> ```php
> namespace Methodz\Helpers\Date;
> 
> class DateTime extends \DateTime
> {
> 	private string $datetime;
> 
> 	public function __construct(string $datetime = 'now');
> 
> 	public function formatMin(string $format = 'Y-m-d'): string;
> 	public function formatMax(string $format = 'Y-m-d H:i:s'): string;
> 	public function formatFrenchMin(string $format = 'd/m/Y'): string;
> 	public function formatFrenchMax(string $format = 'H:i:s d/m/Y'): string;
> 	public function isValidDateTime(): bool;
> 	public function isBefore(string|DateTime $datetime): bool;
> 	public function isAfter(string|DateTime $datetime): bool;
> 	public function equals(string|DateTime $datetime): bool;
> 	public function setDate(int $year, int $month, int $day): self;
> 	public function setTimestamp(int $timestamp): self;
> 
> 	public static function now(): self;
> 	public static function createFromFormat(string $format, string $datetime, \DateTimeZone|null $timezone = null): self;
> 	public static function createFromTimestamp(int $timestamp): self;
> }
> ```

> ### <span id="directory">**Directory**</span>
> ```php
> namespace Methodz\Helpers\File;
> 
> abstract class Directory
> {
> 	public static function create(string $path, int $permission = 0777): bool;
> 	public static function delete(string $path): bool;
> 	public static function exist(string $path): bool;
> }
> ```

> ### <span id="file">**File**</span>
> ```php
> namespace Methodz\Helpers\File;
> 
> abstract class File
> {
> 	public static function upload(string $path, string $fileName, string $tmp_name): bool;
> 	public static function put(string $path, string $fileName, string $content): bool|int;
> 	public static function get(string $path, ?string $fileName = null): bool|string;
> 	public static function delete(string $path, ?string $fileName = null): bool;
> }
> ```

> ### <span id="mail">**Mail**</span>
> ```php
> namespace Methodz\Helpers\Mail;
> 
> class Mail
> {
> 	public function setFromMail(string $fromMail): self;
> 	public function setFromName(string $fromName): self;
> 	public function send(): bool;
> 
> 	public static function create(array $to, string $fromMail, string $subject, string $body, bool $isHTML = false): self;
> }
> ```

> ### <span id="url">**Url**</span>
> ```php
> namespace Methodz\Helpers\Curl;
> 
> class Url
> {
> 	private UrlSchemeEnum $scheme;
> 	private ?string $user;
> 	private ?string $pass;
> 	private string $host;
> 	private ?int $port;
> 	private ?string $path;
> 	private ?array $data = null;
> 	private ?string $fragment;
> 
> 	public function setHeader(array $header): self;
> 	public function addHeader(CurlCommonHeaderKeyEnum|string $key, mixed $value): self;
> 	public function setHtaccessUsernameAndPassword(string $username, string $password): self;
> 	public function setScheme(UrlSchemeEnum $scheme): self;
> 	public function setUser(string|null $user): self;
> 	public function setPass(string|null $pass): self;
> 	public function setHost(string|null $host): self;
> 	public function setPort(int|null $port): self;
> 	public function setPath(string|null $path): self;
> 	public function setParameters(?array $data): self;
> 	public function addParameters(string $key, mixed $value): self;
> 	public function setFragment(string|null $fragment): self;
>  	public function build(): string;
> 
> 	public static function from(string $url): self;
> }
> ```

> ### <span id="uuid">**UUID**</span>
> ```php
> namespace Methodz\Helpers\Tools;
> 
> abstract class UUID
> {
> 	private static array $history = [];
> 
> 	public static function generate(int $length = 30): string;
> 	public static function getHistory(): array;
> 	public static function getLastUUID(): ?string;
> }
> ```
