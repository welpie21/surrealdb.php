<?php

namespace Surreal;

class SurrealAuthorization
{
    /**
     * Holds the authorization token for the current connection.
     * @var string|null
     */
    private ?string $authorization = null;

    /**
     * Holds the scope for the current connection.
     * @var string|null
     */
    private ?string $scope = null;

    /**
     * Sets the authorization token for the current connection.
     * @param string $token
     * @return void
     */
    public function setAuthToken(string $token): void
    {
        $this->authorization = "Bearer $token";
    }

    /**
     * Returns the authorization token for the current connection.
     * @return string|null
     */
    public function getAuthToken(): string|null
    {
        return $this->authorization;
    }

    /**
     * Sets the scope for the current connection.
     * @param string $scope
     * @return void
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * Invalidates the authorization token for the current connection.
     * @return void
     */
    public function invalidate(): void
    {
        $this->authorization = null;
    }
}