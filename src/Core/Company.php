<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\GeneratorInterface;

class Company implements CompanyExtensionInterface
{
    public function __construct(
        protected RandomizerInterface $randomizer,
        protected GeneratorInterface $generator
    ) {
    }

    /** @var string[]  */
    protected array $formats = [
        '{{lastName}} {{companySuffix}}',
    ];

    /** @var string[]  */
    protected array $companySuffix = ['Ltd'];

    /** @var string[]  */
    protected array $jobTitleFormat = [
        '{{word}}',
    ];

    public function company(): string
    {
        $format = $this->randomizer->randomElement($this->formats);

        return $this->generator->parse($format);
    }

    public function companySuffix(): string
    {
        return $this->randomizer->randomElement($this->companySuffix);
    }

    public function jobTitle(): string
    {
        $format = $this->randomizer->randomElement($this->jobTitleFormat);

        return $this->generator->parse($format);
    }

    /** @var string[] */
    protected array $industries = [
        'Technology', 'Healthcare', 'Financial Services', 'Manufacturing',
        'Retail', 'Telecommunications', 'Education', 'Energy',
        'Transportation', 'Media & Entertainment', 'Real Estate', 'Agriculture',
        'Hospitality', 'Construction', 'Consulting',
    ];

    /** @var array<int, string[]> */
    protected array $catchPhraseWords = [
        ['Adaptive', 'Advanced', 'Automated', 'Balanced', 'Business-focused', 'Centralized', 'Compatible', 'Configurable', 'Cross-platform', 'Customer-focused', 'Customizable', 'Decentralized', 'Digitized', 'Distributed', 'Diverse', 'Dynamic', 'Empowered', 'Enhanced', 'Enterprise-wide', 'Ergonomic', 'Exclusive', 'Expanded', 'Extended', 'Focused', 'Front-line', 'Fully-configurable', 'Fundamental', 'Future-proofed', 'Horizontal', 'Innovative', 'Integrated', 'Intuitive', 'Managed', 'Monitored', 'Multi-channel', 'Multi-lateral', 'Multi-layered', 'Multi-tiered', 'Networked', 'Object-based', 'Open-architected', 'Open-source', 'Operative', 'Optimized', 'Organic', 'Organized', 'Proactive', 'Profit-focused', 'Programmable', 'Progressive', 'Quality-focused', 'Reactive', 'Realigned', 'Re-engineered', 'Robust', 'Seamless', 'Secured', 'Self-enabling', 'Sharable', 'Stand-alone', 'Streamlined', 'Switchable', 'Synchronized', 'Synergistic', 'Team-oriented', 'Total', 'Universal', 'Upgradable', 'User-centric', 'User-friendly', 'Versatile', 'Virtual', 'Visionary'],
        ['24hour', '24/7', 'actuating', 'analyzing', 'asynchronous', 'client-driven', 'client-server', 'coherent', 'cohesive', 'composite', 'context-sensitive', 'content-based', 'dedicated', 'demand-driven', 'directional', 'discrete', 'dynamic', 'eco-centric', 'empowering', 'encompassing', 'executive', 'explicit', 'fault-tolerant', 'fresh-thinking', 'full-range', 'global', 'grid-enabled', 'heuristic', 'high-level', 'holistic', 'homogeneous', 'hybrid', 'impactful', 'incremental', 'interactive', 'intermediate', 'leading-edge', 'local', 'logistical', 'maximized', 'methodical', 'mission-critical', 'mobile', 'modular', 'motivating', 'multimedia', 'multi-tasking', 'national', 'needs-based', 'neutral', 'next-generation', 'object-oriented', 'optimal', 'optimizing', 'radical', 'real-time', 'reciprocal', 'regional', 'responsive', 'scalable', 'secondary', 'solution-oriented', 'stable', 'systemic', 'systematic', 'tangible', 'transitional', 'uniform', 'upward-trending', 'user-facing', 'value-added', 'web-enabled', 'well-modulated', 'zero-defect'],
        ['ability', 'access', 'adapter', 'algorithm', 'alliance', 'analyzer', 'application', 'approach', 'architecture', 'archive', 'artificial intelligence', 'attitude', 'benchmark', 'blockchain', 'capability', 'capacity', 'challenge', 'circuit', 'collaboration', 'complexity', 'concept', 'conglomeration', 'contingency', 'core', 'customer loyalty', 'database', 'data-warehouse', 'definition', 'emulation', 'encoding', 'encryption', 'extranet', 'firmware', 'flexibility', 'forecast', 'frame', 'framework', 'function', 'functionality', 'groupware', 'hardware', 'help-desk', 'hierarchy', 'hub', 'implementation', 'infrastructure', 'initiative', 'installation', 'instruction set', 'interface', 'internet solution', 'intranet', 'knowledge base', 'leverage', 'local area network', 'matrix', 'methodology', 'middleware', 'migration', 'model', 'moderator', 'monitoring', 'neural-net', 'open architecture', 'open system', 'orchestration', 'paradigm', 'parallelism', 'policy', 'portal', 'pricing structure', 'process improvement', 'product', 'productivity', 'project', 'projection', 'protocol', 'service-desk', 'software', 'solution', 'standardization', 'strategy', 'structure', 'success', 'superstructure', 'support', 'synergy', 'system', 'task-force', 'throughput', 'time-frame', 'toolset', 'website', 'workforce'],
    ];

    public function industry(): string
    {
        return $this->randomizer->randomElement($this->industries);
    }

    public function catchPhrase(): string
    {
        $words = [];
        foreach ($this->catchPhraseWords as $wordList) {
            $words[] = $this->randomizer->randomElement($wordList);
        }

        return implode(' ', $words);
    }
}

