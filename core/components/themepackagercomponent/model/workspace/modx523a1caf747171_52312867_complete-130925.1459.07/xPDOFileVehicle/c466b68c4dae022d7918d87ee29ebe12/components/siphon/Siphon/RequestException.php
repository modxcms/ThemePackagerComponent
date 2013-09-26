<?php
namespace Siphon;

/**
 * The Siphon Request Exception handler.
 *
 * Adds the current request results for reporting.
 */
class RequestException extends SiphonException {
    /** @var array A reference to an array of results */
    protected $results;

    /**
     * Construct a new Siphon\RequestException
     *
     * @param $message The exception message.
     * @param array &$results A reference to an array of results.
     * @param null|\Exception $previous An optional \Exception to nest.
     */
    public function __construct($message, array &$results = array(), $previous = null) {
        parent::__construct($message, E_USER_ERROR, $previous);
        $this->results =& $results;
    }

    /**
     * Return the results stored with the exception.
     *
     * @return array
     */
    public function getResults() {
        return $this->results;
    }
}