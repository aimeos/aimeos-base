<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Attribute;


/**
 * Interface for search attribute classes.
 *
 * @package Base
 * @subpackage Common
 */
interface Iface
{
	/**
	 * Returns the public code for the search attribute.
	 *
	 * @return string Public code of the search attribute
	 */
	public function getCode() : string;

	/**
	 * Returns the default value of the search attribute.
	 *
	 * @return mixed Default value of the search attribute
	 */
	public function getDefault();

	/**
	 * Returns the helper function if available
	 *
	 * @return \Closure|null Helper function
	 */
	public function getFunction() : ?\Closure;

	/**
	 * Returns the internal code for the search attribute.
	 *
	 * @return array|string Internal code of the search attribute
	 */
	public function getInternalCode();

	/**
	 * Returns the list of internal dependencies.
	 *
	 * @return array List of dependency strings
	 */
	public function getInternalDeps() : array;

	/**
	 * Returns the type internally used by the manager.
	 *
	 * @return int|string Type used by the manager
	 */
	public function getInternalType();

	/**
	 * Returns the human readable label for the search attribute.
	 *
	 * @return string Name of the search attribute
	 */
	public function getLabel() : string;

	/**
	 * Returns the type of the attribute.
	 *
	 * Can be used in the frontend to create a speacial form for this type
	 *
	 * @return string Available types are "string", "integer", "float", "boolean", "date", "time", "datetime"
	 */
	public function getType() : string;

	/**
	 * Returns true if the attribute is for public use.
	 *
	 * @return boolean True if the attribute is public, false if not
	 */
	public function isPublic() : bool;

	/**
	 * Returns true if the attribute is required.
	 *
	 * @return boolean True if the attribute is required, false if not
	 */
	public function isRequired() : bool;

	/**
	 * Returns the attribute properties as key/value pairs.
	 *
	 * @param bool $private TRUE to return private attributes too, FALSE for public only
	 * @return array Associative list of attribute key/value pairs
	 */
	public function toArray( bool $private = false ) : array;
}
