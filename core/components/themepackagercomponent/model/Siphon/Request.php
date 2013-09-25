<?php
namespace Siphon;

/**
 * CLI request handler for MODX Siphon
 */
class Request {
    /** @var string The action for Siphon to take */
    public $action;
    /** @var array Results of the action */
    public $results = array();
    /** @var array An array of arguments for the action */
    protected $arguments;

    /**
     * Parse PHP CLI arguments into an associative array.
     *
     * @static
     * @param array $args Raw CLI arguments.
     * @return array An associative array of argument key/value pairs.
     */
    public static function parseArguments(array $args) {
        $parsed = array();
        $argument = reset($args);
        while ($argument) {
            if (strpos($argument, '=') > 0) {
                $arg = explode('=', $argument);
                $argKey = ltrim($arg[0], '-');
                $argValue = trim($arg[1], '"');
                $parsed[$argKey] = $argValue;
            } else {
                $parsed[ltrim($argument, '-')] = true;
            }
            $argument = next($args);
        }
        return $parsed;
    }

    /**
     * Construct a new Siphon\Request instance.
     *
     * @param array|null $arguments An array of arguments for the request.
     */
    public function __construct($arguments = null) {
        if (!is_array($arguments)) {
            throw new RequestException('No arguments received for Siphon request.', $this->results);
        }
        if (!array_key_exists('action', $arguments)) {
            throw new RequestException('No valid action argument received for Siphon request.', $this->results);
        }
        $this->action = $arguments['action'];
        unset($arguments['action']);
        $this->arguments = $arguments;
    }

    /**
     * Overridden to return arguments by name.
     *
     * @param string $name The name of the argument.
     * @return mixed The value of the argument.
     * @throws RequestException If the argument does not exist.
     */
    public function __get($name) {
        $value = null;
        if (array_key_exists($name, $this->arguments)) {
            $value = $this->arguments[$name];
        }
        return $value;
    }

    /**
     * Overridden to set an argument by name.
     *
     * @param string $name The name of the argument.
     * @param mixed $value The value to assign the argument.
     */
    public function __set($name, $value) {
        if (!empty($name)) {
            $this->arguments[$name] = $value;
        }
    }

    public function __isset($name) {
        $isset = false;
        if (!empty($name)) {
            $isset = array_key_exists($name, $this->arguments);
        }
        return $isset;
    }

    /**
     * Get an argument, array of arguments by key, or all arguments for the request.
     *
     * An empty array indicates all arguments should be returned; the default behavior.
     *
     * @param array|string $key A key or array of keys for the arguments to return. An empty array
     * returns all arguments set for the request.
     * @return mixed A value for a single argument, an array of argument values, or null.
     */
    public function args($key = array()) {
        if (is_array($key)) {
            $args = array();
            if (!empty($key)) {
                foreach ($key as $k) $args[$k] = $this->args($k);
            } else {
                $args = $this->arguments;
            }
            return $args;
        } elseif (is_string($key) && !empty($key)) {
            return $this->$key;
        } else {
            return null;
        }
    }

    /**
     * Switch user
     *
     * If username arg is provided, attempt to switch to this user via posix_ functions
     *
     * @return boolean True if the user and group were successfully switched
     */
    public function switchUser() {
        if ($this->username) {
            if (function_exists('posix_getpwnam') && $u = @posix_getpwnam($this->username)) {
                $uidswitch = @posix_setuid($u['uid']);
                $gidswitch = @posix_setgid($u['gid']);
            }
        }
        $current_user = @posix_getpwuid(@posix_getuid());
        $this->log('Siphon running as ' . $current_user['name']);
        return true;
    }

    /**
     * Handle the CLI request.
     *
     * @throws RequestException If the action is invalid or an error is encountered during processing.
     */
    public function handle() {
        $actionClass = "\\Siphon\\Actions\\" . str_replace('/', '\\', $this->action);
        if (class_exists($actionClass, true)) {
            try {
                /** @var \Siphon\Actions\Action $handler */
                $handler = new $actionClass($this);
                $handler->process();
            } catch (\Exception $e) {
                throw new RequestException("Error handling {$this->action} Siphon request: " . $e->getMessage(), $this->results, $e);
            }
        } else {
            throw new RequestException("Invalid action {$this->action} specified in Siphon request.", $this->results);
        }
    }

    /**
     * Return the results of the request.
     *
     * @return array An array of results.
     */
    public function getResults() {
        return $this->results;
    }

    /**
     * Log a message into the results.
     *
     * Will echo results as they occur if --verbose is passed.
     *
     * @param string $msg The message to log.
     * @param bool $timestamp
     */
    public function log($msg, $timestamp = true) {
        if ($timestamp) {
            $timestamp = strftime("%Y-%m-%d %H:%M:%S");
            $msg = "[{$timestamp}] {$msg}";
        }
        $this->results[] = $msg;
        if ($this->verbose) echo $msg;
    }
}
