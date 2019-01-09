<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 27/12/18
	 * Time: 11.11
	 */

	namespace Core\Services\Status;


	/**
	 * Class StatusService
	 * @package Core\Services\Status
	 */
	class StatusService
	{
		/**
		 * @var bool
		 */
		protected $success;

		/**
		 * @var array
		 */
		protected $data;

		/**
		 * @var string
		 */
		protected $message;

		/**
		 * @var string
		 */
		protected $statusCode;

		/**
		 * StatusService constructor.
		 *
		 * @param bool|null   $success
		 * @param int|null    $statusCode
		 * @param array       $data
		 * @param null|string $message
		 *
		 * @throws \Exception
		 */
		public function __construct(?bool $success = null, ?int $statusCode = null, array $data = array(), ?string $message = null)
		{
			$this->success = $success;
			$this->data = $data;
			$this->message = $message;

			if (!is_int($statusCode) && !is_null($statusCode))
				throw new \Exception("Error statusCode: (" . gettype($statusCode) . ") " . $statusCode . ", will be int or null");
			$this->statusCode = $statusCode;
		}

		/**
		 *
		 *  Instances factory.
		 *
		 * @param bool|null   $success
		 * @param int|null    $statusCode
		 * @param array       $data
		 * @param null|string $message
		 *
		 * @return \Core\Services\Status\StatusService
		 * @throws \Exception
		 */
		public static function set(?bool $success, ?int $statusCode = null, array $data = array(), ?string $message = null)
		{
			return new StatusService($success, $statusCode, $data, $message);
		}

		/**
		 * @param string|array $key
		 *    Optional key in the data associative array.
		 *    Key maybe use DOT notation
		 *
		 * @return array
		 *    The data array or the requested item if $key is set.
		 */
		public function data($keys = null):array
		{

			if (is_string($keys)) {
				$keys = (array) $keys;
			}
			if (is_array($keys)) {
				$data = array();
				foreach ($keys as $key) {
					$name = explode('.', $key);
					$data[last($name)] = array_get($this->data, $key, null);
				}
				return $data;
			}

			return $this->data;
		}

		/**
		 * @param int $withStatusCode
		 *    The status code to be searched.
		 *
		 * @return bool
		 *    TRUE if service method failed.
		 *    When $with is provided, returns TRUE when
		 *    method failed (AND) with the specified
		 *    status code.
		 */
		public function fail(int $withStatusCode = null): bool
		{
			if ($withStatusCode) {
				return !$this->success && $withStatusCode === $this->statusCode;
			}

			return !$this->success;
		}

		/**
		 * @return string
		 *        The message.
		 */
		public function message(): ?string
		{
			return $this->message;
		}

		/**
		 * @return int
		 *        The service status.
		 */
		public function status(): ?int
		{
			return $this->statusCode;
		}

		/**
		 * @param string $with
		 *    The status code to be searched.
		 *
		 * @return bool
		 *    TRUE if service method ran successfully.
		 *    When $with is provided, returns TRUE when
		 *    method ran successfully AND with the specified
		 *    status code.
		 */
		public function success(int $withStatusCode = null): bool
		{
			if ($withStatusCode) {
				return $this->success && $withStatusCode === $this->statusCode;
			}

			return $this->success;
		}

		/**
		 * @return array
		 */
		public function toArray()
		{
			return array(
				'success' => $this->success,
				'data' => $this->data,
				'message' => $this->message,
				'statusCode' => $this->statusCode,
			);
		}

		/**
		 * @return string
		 */
		public function __toString()
		{
			$toString = json_encode($this->toArray(),JSON_FORCE_OBJECT);
			return $toString;
		}

	}