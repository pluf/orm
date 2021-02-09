<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Orm;

use Throwable;
use JsonSerializable;
use RuntimeException;

/**
 * Pluf root exception type
 *
 * All pluf application exceptions are subclass of the \Pluf\Data\Exception. If any exeption throw
 * which is not subclass of it, the framework will consider as non expected exception.
 *
 *
 * @author Mostafa Barmshory<mostafa.barmshory@dpq.co.ir>
 * @since Pluf6
 *       
 */
class Exception extends RuntimeException implements JsonSerializable
{

    private array $solutions = [];

    private array $params = [];

    /**
     * Crates new instance of the exception
     *
     * @param string $message
     *            the message to show
     * @param int $code
     *            the error code
     * @param Throwable $previous
     *            the cause root of the error
     * @param int $status
     *            the status code based on HTTP status for example 500 for internal error and 400 for user errors.
     * @param array $params
     *            parameters that is used in message and solutions
     * @param array $solutions
     *            list of common way to solve the problem
     */
    public function __construct($message = '', ?int $code = null, ?Throwable $previous = null, ?array $params = [], ?array $solutions = [])
    {
        if (is_array($message)) {
            // message contain additional parameters
            $params = $message;
            $message = array_shift($this->params);
        }
        parent::__construct($message, $code ?? 0, $previous);
        $this->params = $params;
        $this->solutions = $solutions;
    }

    /**
     * Follow the getter-style of PHP Exception.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Gets solustions
     *
     * @return array
     */
    public function getSolutions(): array
    {
        return $this->solutions;
    }

    /**
     *
     * {@inheritdoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'parmas' => $this->params,
            'solutions' => $this->getSolutions()
        ];
    }

    public function jsonSerializeDebug()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'parmas' => $this->getParams(),
            'solutions' => $this->getSolutions(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'stack' => $this->getTrace()
        ];
    }

    /**
     *
     * {@inheritdoc}
     * @see RuntimeException::__toString()
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_PRETTY_PRINT);
    }
}



