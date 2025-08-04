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
                        $parameter['default'] = $reflparameter->getDefaultValue();
                    }

                    $parameters[] = $parameter;
                }

                $parametersJoined = [];

                foreach ($parameters as $parameter) {
                    if ($this->withParamTypes && !empty($parameter['type'])) {
                        $parametersString = $parameter['type'] . ' ' . $parameter['name'];
                    } else {
                        $parametersString = $parameter['name'];
                    }

                    if (!empty($parameter['default'])) {
                        $parametersString .= ' = ' . $parameter['default'];
                    }

                    $parametersJoined[] = $parametersString;
                }

                $parametersString = implode(', ', $parametersJoined);

                try {
                    $example = $generator->$methodName();
                } catch (\Throwable $e) {
                    $errors[$methodName] = $e->getMessage();
                    $example = '';
                }

                $example = $this->formatExample($example);
                $returnTypes = $this->getTypes($reflmethod->getReturnType());

                $extensions[$id][$methodName . ' (' . $parametersString . ')'] = '(' . $returnTypes . ') ' . $example;
            }
        }

        // it might be easier to go with this, instead of returning
        // file_put_contents('./docs/extensions_spec_'.date('Ymd').'.txt', print_r($extensions, true));
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
}
