# Event Engine - PHP InspectIO Graph

This repository contains interfaces for the *InspectIO Graph* specification. Based on this specification different 
file formats like XML or JSON can be supported.

There are two implementations of the *InspectIO Graph* specification: 

- [InspectIO GraphML graph format](https://github.com/event-engine/php-inspectio-graph-ml "InspectIO Graph GraphML"): Implementation based on GraphML
- [InspectIO Cody graph format](https://github.com/event-engine/php-inspectio-graph-cody "InspectIO Graph Cody"): Implementation based on JSON structure (recommended)

Code generation libraries based on the *InspectIO Graph* format:

- [Event Engine - PHP Code Generator via PHP AST](https://github.com/event-engine/php-code-generator-event-engine-ast "Event Engine - PHP Code Generator via PHP AST"): PHP Code Generation for [Event Engine](https://event-engine.io "Event Engine").

## Installation

```bash
$ composer require event-engine/php-inspectio-graph
```
