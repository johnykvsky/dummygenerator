<?php

declare(strict_types=1);

namespace Script;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Container\DefinitionContainerBuilder;
use DummyGenerator\Container\DefinitionContainerInterface;
use DummyGenerator\DefinitionPack\DefinitionPack;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\DummyGenerator;

class ExtensionsDocs
{
    private bool $withParamTypes = false;
    private DefinitionContainerInterface $definitionContainer;

    public function __construct(?DefinitionContainer $definitionContainer = null)
    {
        if ($definitionContainer === null) {
            $this->definitionContainer = DefinitionContainerBuilder::all();
        }
    }

    public function withParamTypes(): self
    {
        $this->withParamTypes = true;

        return $this;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getExtensions(): array
    {
        $generator = new DummyGenerator($this->definitionContainer);
        $definitions = $this->getDefinitions();

        $extensions = [];
        $errors = [];

        foreach ($definitions as $id => $extension) {
            if (!$this->definitionContainer->has($id)) {
                continue;
            }

            $extensionClass = $this->definitionContainer->get($id);

            if (!$extensionClass instanceof ExtensionInterface) {
                continue;
            }

            $extensions[$id] = [];
            $refl = new \ReflectionObject($extensionClass);

            foreach ($refl->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflmethod) {
                $methodName = $reflmethod->name;

                if ($reflmethod->isConstructor() || str_starts_with($methodName, 'with')) {
                    continue;
                }
                $parameters = [];

                foreach ($reflmethod->getParameters() as $reflparameter) {
                    $parameter = [];
                    $parameter['name'] = '$' . $reflparameter->getName();
                    $parameter['type'] = $this->getTypes($reflparameter->getType());


                    if ($reflparameter->isDefaultValueAvailable()) {
                        $defaultValue = $reflparameter->getDefaultValue();
                        if (is_string($defaultValue)) {
                            $defaultValue = '"' . $defaultValue . '"';
                        }

                        if (is_null($defaultValue)) {
                            $defaultValue = 'null';
                        }

                        if (is_bool($defaultValue)) {
                            $defaultValue = $defaultValue ? 'true' : 'false';
                        }

                        $parameter['default'] = $defaultValue;
                    }

                    $parameters[] = $parameter;
                }

                try {
                    $example = $generator->$methodName();
                } catch (\Throwable $e) {
                    $errors[$methodName] = $e->getMessage();
                    $example = '';
                }

                $example = $this->formatExample($example);
                $returnTypes = $this->getTypes($reflmethod->getReturnType());

                $extensions[$id][$methodName] = [
                    'methodName' => $methodName,
                    'parameters' => $parameters,
                    'returnType' => $returnTypes,
                    'example' => $example,
                ];
            }
        }

        file_put_contents('./docs/extensions_list.md', $this->toMarkdown($extensions));
        // var_export($errors);

        return $extensions;
    }

    /**
     * @return array<string, class-string<ExtensionInterface>>
     */
    private function getDefinitions(): array
    {
        $definitions = [];
        $definitionPack = new DefinitionPack();
        $definitions = array_merge($definitions, $definitionPack->baseExtensions());
        $definitions = array_merge($definitions, $definitionPack->defaultExtensions());
        $definitions = array_merge($definitions, $definitionPack->complementaryExtensions());

        return $definitions;
    }

    private function getTypes(?\ReflectionType $type): string
    {
        $result = null;

        if ($type instanceof \ReflectionNamedType) {
            if (!in_array($type->getName(), ['array','string','bool','int','float'])) {
                return '\\'.$type->getName();
            }
            return $type->getName();
        }

        $multiple = [];

        if ($type instanceof \ReflectionUnionType || $type instanceof \ReflectionIntersectionType) {
            foreach ($type->getTypes() as $item) {
                $multiple[] = $this->getTypes($item);
            }
            $result = implode('|', $multiple);
        }

        return $result ?? 'mixed';
    }

    private function formatExample(mixed $example): string
    {
        if (is_array($example)) {
            $result = "['" . implode("', '", $example) . "']";
        } elseif ($example instanceof \DateTimeInterface) {
            $result = "DateTimeImmutable('" . $example->format('Y-m-d H:i:s') . "')";
        } else {
            $result = var_export($example, true);
        }

        return $result;
    }

    private function toMarkdown(array $data): string
    {
        $markdown = '';

        $sortedData = $this->sortExtensions($data);

        foreach ($sortedData as $shortName => $info) {
            $markdown .= "## {$shortName}\n\n";
            ksort($info['methods'], SORT_NATURAL | SORT_FLAG_CASE);
            foreach ($info['methods'] as $method => $value) {
                $methodName = $value['methodName'] . '('.$this->getParametersString($value['parameters'], $this->withParamTypes).')';
                $returnData = '('.$value['returnType'].') ' . $value['example'];
                $markdown .= "- `{$methodName}`: {$returnData}\n";
            }

            $markdown .= "\n";
        }

        return $markdown;
    }

    private function sortExtensions(array $data): array
    {
        $sortedData = [];
        foreach ($data as $interface => $methods) {
            if (preg_match('/\\\\(\w+)ExtensionInterface$/', $interface, $matches)) {
                $shortName = $matches[1];
            } else {
                $shortName = $interface;
            }
            $sortedData[$shortName] = [
                'full' => $interface,
                'methods' => $methods
            ];
        }
        ksort($sortedData, SORT_NATURAL | SORT_FLAG_CASE);

        return $sortedData;
    }

    private function getParametersString(array $parameters, $withParamTypes = false): string
    {
        $parametersJoined = [];

        foreach ($parameters as $parameter) {
            if ($withParamTypes && !empty($parameter['type'])) {
                if (!in_array($parameter['type'], ['array','string','bool','int','float'])) {
                    $parameter['type'] = '\\'.$parameter['type'];
                }

                $parametersString = $parameter['type'] . ' ' . $parameter['name'];
            } else {
                $parametersString = $parameter['name'];
            }

            if (isset($parameter['default'])) {
                $parametersString .= ' = ' . $parameter['default'];
            }

            $parametersJoined[] = $parametersString;
        }

        return implode(', ', $parametersJoined);
    }
}
