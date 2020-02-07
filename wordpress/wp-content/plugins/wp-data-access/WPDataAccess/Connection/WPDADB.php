<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess
 */

namespace WPDataAccess\Connection {

	use WPDataAccess\WPDA;

	/**
	 * Class WPDADB
	 *
	 * Manage local and remote database connections.
	 *
	 * @author  Peter Schulz
	 * @since   3.0.0
	 */
	class WPDADB {

		/**
		 * Database connections cached per database name (schema_name)
		 *
		 * Remote database are prefixed with "rdb:"
		 *
		 * @var array
		 */
		static protected $db_connections = [];

		/**
		 * Remote database access definitions
		 *
		 * @var array|bool
		 */
		static protected $remote_databases = false;

		/**
		 * Stores te lower_case_table_names db value
		 *
		 * @var null|int
		 */
		static protected $lower_case_table_names = null;

		/**
		 * Encrypt a string with the WPDA secret key and iv
		 *
		 * @param string $string String to be encrypted
		 *
		 * @return string
		 */
		public static function string_encrypt( $string ) {
			$secret_key = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_KEY );
			$secret_iv  = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_IV );

			$encrypt_method = "AES-256-CBC";
			$key            = hash( 'sha256', $secret_key );
			$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

			return base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}

		/**
		 * Decrypt a string with the WPDA secret key and iv
		 *
		 * @param string $string String to be decrypted
		 *
		 * @return string
		 */
		public static function string_decrypt( $string ) {
			$secret_key = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_KEY );
			$secret_iv  = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_IV );

			$encrypt_method = "AES-256-CBC";
			$key            = hash( 'sha256', $secret_key );
			$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

			return openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}

		public static function load_remote_databases() {
			if ( false === self::$remote_databases ) {
				$decrypted_databases = [];
				$encrypted_databases = get_option( 'wpda_remote_databases' );
				if ( false !== $encrypted_databases ) {
					foreach ( $encrypted_databases as $key => $val ) {
						$decrypted_databases[ self::string_decrypt( $key ) ] = [
							'host'     => self::string_decrypt( $val[0] ),
							'username' => self::string_decrypt( $val[1] ),
							'password' => self::string_decrypt( $val[2] ),
							'port'     => self::string_decrypt( $val[3] ),
							'database' => self::string_decrypt( $val[4] ),
							'disabled' => $val[5],
						];
					}
				}
				self::$remote_databases = $decrypted_databases;
			}
		}

		public static function save_remote_databases() {
			self::load_remote_databases();

			$encrypted_databases = [];
			foreach ( self::$remote_databases as $key => $val ) {
				$encrypted_databases[ self::string_encrypt( $key ) ] = [
					0 => self::string_encrypt( $val[ 'host' ] ),
					1 => self::string_encrypt( $val[ 'username' ] ),
					2 => self::string_encrypt( $val[ 'password' ] ),
					3 => self::string_encrypt( $val[ 'port' ] ),
					4 => self::string_encrypt( $val[ 'database' ] ),
					5 => $val[ 'disabled' ],
				];
			}
			update_option( 'wpda_remote_databases', $encrypted_databases );
		}

		public static function get_remote_databases( $include_disabled = false ) {
			self::load_remote_databases();

			if ( $include_disabled ) {
				return self::$remote_databases;
			} else {
				$exclude_disabled = self::$remote_databases;
				foreach ( self::$remote_databases as $key => $remote_database ) {
					if ( $remote_database['disabled'] ) {
						unset( $exclude_disabled[ $key ] );
					}
				}
				return $exclude_disabled;
			}
		}

		public static function get_remote_database( $database, $include_disabled = false ) {
			self::load_remote_databases();

			if ( isset( self::$remote_databases[ $database ] ) ) {
				if ( $include_disabled ) {
					return self::$remote_databases[ $database ];
				} else {
					if ( ! self::$remote_databases[ $database ]['disabled'] ) {
						return self::$remote_databases[ $database ];
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
		}

		public static function add_remote_database( $database, $host, $user, $passwd, $port, $schema ) {
			self::load_remote_databases();

			if ( false === self::get_remote_database( $database ) ) {
				self::$remote_databases[ $database ] = [
					'host'     => $host,
					'username' => $user,
					'password' => $passwd,
					'port'     => $port,
					'database' => $schema,
					'disabled' => false,
				];
				self::save_remote_databases();

				return true;
			} else {
				return false;
			}
		}

		public static function del_remote_database( $database ) {
			self::load_remote_databases();

			if ( false === self::get_remote_database( $database, true ) ) {
				return false;
			} else {
				unset( self::$remote_databases[ $database ] );
				self::save_remote_databases();

				return true;
			}
		}

		public static function upd_remote_database( $database, $host, $user, $passwd, $port, $schema, $disabled, $database_old = false ) {
			self::load_remote_databases();

			if ( false !== $database_old && $database !== $database_old ) {
				self::add_remote_database( $database, $host, $user, $passwd, $port, $schema );
				self::del_remote_database( $database_old );

				return true;
			} else {
				if ( false === self::get_remote_database( $database, true ) ) {
					return false;
				} else {
					self::$remote_databases[ $database ] = [
						'host'     => $host,
						'username' => $user,
						'password' => $passwd,
						'port'     => $port,
						'database' => $schema,
						'disabled' => $disabled
					];
					self::save_remote_databases();

					return true;
				}
			}
		}

		/**
		 * Get database connection
		 *
		 * Remote schema name starts with prefix "rdb:"
		 *
		 * @param string $schema_name Database (schema) name
		 *
		 * @return mixed|\wpdb
		 */
		public static function get_db_connection( $schema_name ) {
			global $wpdb;
			if ( 'rdb:' === substr( $schema_name, 0, 4 ) ) {
				// Remote database (other ip|port)
				self::load_remote_databases();
				if ( ! isset( self::$db_connections[ $schema_name ] ) ) {
					if ( isset( self::$remote_databases[ $schema_name ] ) ) {
						$host = self::$remote_databases[ $schema_name ]['host'];
						if (
							self::$remote_databases[ $schema_name ]['port'] !== '' &&
							self::$remote_databases[ $schema_name ]['port'] !== '3306'
						) {
							$host .= ':' . self::$remote_databases[ $schema_name ]['post'];
						}
						self::$db_connections[ $schema_name ] = new \wpdb(
							self::$remote_databases[ $schema_name ]['username'],
							self::$remote_databases[ $schema_name ]['password'],
							self::$remote_databases[ $schema_name ]['database'],
							$host
						);
					} else {
						// Remote schema name not found, return default schema
						return $wpdb;
					}
				}

				return self::$db_connections[ $schema_name ];
			} else {
				// Database runs in local WordPress instance
				if ( '' === $schema_name || null === $schema_name || self::iswpdb( $schema_name ) ) {
					return $wpdb;
				} else {
					if ( ! isset( self::$db_connections[ $schema_name ] ) ) {
						self::$db_connections[ $schema_name ] = new \wpdb( DB_USER, DB_PASSWORD, $schema_name, DB_HOST );
					}

					return self::$db_connections[ $schema_name ];
				}
			}
		}

		public static function iswpdb( $schema_name ) {
			global $wpdb;
			if ( null === self::$lower_case_table_names ) {
				$lower_case_table_names = $wpdb->get_results( "SHOW VARIABLES LIKE 'lower_case_table_names'", 'ARRAY_N' );
				if ( is_array( $lower_case_table_names ) && isset( $lower_case_table_names[0][1] ) ) {
					self::$lower_case_table_names = $lower_case_table_names[0][1];
				} else {
					self::$lower_case_table_names = 0;
				}
			}
			switch ( self::$lower_case_table_names ) {
				case 1:
				case 2:
					return strtolower( $wpdb->dbname ) === $schema_name;
					break;
				default:
					return $wpdb->dbname === $schema_name;
			}
		}

		/**
		 * Check if a connection with a remote database can be established
		 *
		 * @return \wpdb
		 */
		public function check_remote_database_connection() {
			echo 'Preparing connection...<br/>';

			$host   = isset( $_REQUEST['host'] ) ? $_REQUEST['host'] : '';
			$user   = isset( $_REQUEST['user'] ) ? $_REQUEST['user'] : '';
			$passwd = isset( $_REQUEST['passwd'] ) ? $_REQUEST['passwd'] : '';
			$port   = isset( $_REQUEST['port'] ) ? $_REQUEST['port'] : '';
			$schema = isset( $_REQUEST['schema'] ) ? $_REQUEST['schema'] : '';

			if ( '' === $host || '' === $user || '' === $passwd || '' === $schema ) {
				return false;
			}

			if ( $port !== '' && $port !== '3306' ) {
				$host .= ':' . $port;
			}

			echo 'Establishing connection...<br/>';

			$wpdadb = new \wpdb( $user, $passwd, $schema, $host );

			echo 'Connection established...<br/>';
			echo 'Counting tables...<br/>';

			$query = $wpdadb->prepare(
				'select 1 from information_schema.tables where table_schema = %s',
				[
					$schema
				]
			);
			$wpdadb->get_results( $query, 'ARRAY_A' );

			echo "Found {$wpdadb->num_rows} tables on remote host...<br/><br/>";
			echo '<strong>Remote database connection valid</strong>';
		}

	}

}