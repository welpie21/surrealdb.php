<?php

namespace Surreal\abstracts;

abstract class AbstractTarget
{
    protected ?string $namespace = null;

    protected ?string $database = null;

    protected ?string $scope = null;

    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function setDatabase(?string $database): void
    {
        $this->database = $database;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = $scope;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param AbstractSurreal $base
     * @param AbstractAuth|null $auth
     * @return array{
     *     namespace: string,
     *     database: string,
     *     scope: string
     * }
     */
    public static function parse(AbstractSurreal $base, ?AbstractAuth $auth): array
    {
        $namespace = $auth->getNamespace() ?? $base->getNamespace();
        $database = $auth->getDatabase() ?? $base->getDatabase();
        $scope = $auth->getScope() ?? $base->getScope();

        return [
            "namespace" => $namespace,
            "database" => $database,
            "scope" => $scope
        ];
    }
}