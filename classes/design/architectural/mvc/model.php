<?php

/**
 * Model-view-controller design pattern namespace
 *
 * Model-view-controller (MVC) is a software architecture, currently considered
 * an architectural pattern used in software engineering. The pattern isolates
 * "domain logic" (the application logic for the user) from the user inferface
 * (input and presentation), permitting independent development, testing and
 * maintenance of each (separation of concerns).
 *
 * Model View Controller (MVC) pattern creates applications that separate the
 * different aspects of the application (input logic, business logic, and UI
 * logic), while providing a loose coupling between these elements.
 *
 * @package    Nerd
 * @subpackage MVC
 */
namespace Nerd\Design\Architectural\MVC;

/**
 * Model abstract class
 *
 * In MVC, the model manages the behaviour and data of the application domain,
 * responds to requests for information about its state (usually from the view),
 * and responds to instructions to change (usually from the controller). In
 * event-driven systems, the model notifies observers (usually views) when the
 * information changes so they can react.
 *
 * @package   Nerd
 * @subpackage MVC
 */
abstract class Model
{
}