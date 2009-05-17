<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *  ==========================================
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) 2008 Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 *
 * $Id$
 */

//TODO: Exceptions!!!
//TODO: Opc Exception returns an error with __UNKNOWN__ 

/**
 *
 */	
class Opc_Paginator_Range implements Iterator, Countable, SeekableIterator
{
	/**
	 * This constant means the pager needs to be reset 
	 */	
	const STATE_DIRTY = 1;
	/**
	 * Clean state means the pager is reset and ready to work
	 */	
	const STATE_CLEAN = 2;
	
	/**
	 * Current page	
	 * @access public		 	
	 * @var integer
	 */	
	protected $page = 1;
	/**
	 * Items per page
	 * @access public		 		
	 * @var integer
	 */	
	protected $limit;
	/**
	 * All items to be paginated
	 * @access public		 		
	 * @var integer
	 */	
	protected $all = 0;
	/**
	 * Offset for current page		
	 * @access public		 
	 * @var integer
	 */	
	protected $offset;
	/**
	 * The amount of all pages
	 * @access public		 		
	 * @var integer
	 */			
	protected $pageCount = null;
	/**
	 * Special value of current page for decorators
	 * @access public		 	
	 * @var integer
	 */	
	protected $page_float = null;
	
	// Read-only navigation links
	/**
	 * @access public		
	 * @var array|false
	 */		 		
	protected $first;
	/**
	 * @access public		
	 * @var array|false
	 */
	protected $last;
	/**
	 * @access public		
	 * @var array|false
	 */
	protected $next;
	/**
	 * @access public		
	 * @var array|false
	 */
	protected $previous;
	
	// Internal properties
	/**				
	 * @access private		
	 * @var integer
	 */	
	protected $_state = self::STATE_DIRTY;
	/**
	 * @access private		
	 * @var Opc_Paginator_Decorator 
	 */	
	protected $_decorator = null;
	/**
	 * Internal interator counter
	 * @access private		 
	 * @var integer		 
	 */	
	protected $_i = 0;
	
	/**
	 * Creates new paginator
	 * 		 		
	 * @param integer $all The amout of all items
	 * @param integer $limit Items per page	
	 * @return void		 
	 */	
	public function __construct($all = null, $limit = null)
	{
		if(is_null($limit))
		{
			$limit = Opl_Registry::get('opc')->itemsPerPage;
		}
		
		$this->set('all', $all);
		$this->set('limit', $limit);
	} // end __construct();
	
	/**
	 * Returns currect state. Optional parameter sets the state.
	 * 		 		
	 * @param integer $state A new state		 
	 * @return integer Numeral representation of a state constant 
	 */	
	public function state($state = null)
	{
		switch($state)
		{
			case self::STATE_DIRTY:
				$this->_state = $state;
				break;
			case self::STATE_CLEAN:
				$this->_state = $state;
				break;
			case null:
				break;
			default:
				throw new Opc_Exception('');
				break;				
		}
		
		return $this->_state;
	} // end state();
	
	/**
	 * Magic function so that $obj->key will work
	 *
	 * @param string $key
	 * @return mixed
	 */
	final public function __get($key)
	{
		return $this->get($key);
	} // end __get();

	/**
	 * @param string $key		
	 * @return mixed
	 */	
	public function get($key)
	{
		switch($key)
		{
			case 'pageCount':
				if(is_null($this->pageCount))
				{
					$this->pageCount = ceil($this->get('all') / $this->get('limit'));
				}
				
				return $this->pageCount;
				break;
			case 'page':
				return floor($this->page);
				break;
			case 'page_float':
				return $this->page;
				break;
			case 'decorator':
				if(is_null($this->_decorator))
				{
					$this->set('decorator', $this->decorator);
				}
				
				return $this->_decorator;
				break;
			case 'first':
				if($key == 'first')
				{
					$page = 1;
					if($page == $this->get('page')) return false;
				}
			case 'last':
				if($key == 'last')
				{
					$page = $this->get('pageCount');
					if($page == $this->get('page')) return false;
				}
			case 'previous':
				if($key == 'previous')
				{
					$page = $this->get('page')-1;
					if($page < 1) return false;
				}
			case 'next':
				if($key == 'next')
				{
					$page = $this->get('page')+1;
					if($page > $this->get('pageCount')) return false;
				}
				
				$ret = array(
					'number' => $page,
					'offset' => Opc_Paginator::countOffset($page, $this->get('limit')),
				);
				return $ret;
				break;
			case 'offset':
			case 'limit':
			case 'all':
				break;
			default:
				throw new Opc_OptionNotExists_Exception($key, get_class($this));
				break;
		}
		
		return $this->$key;
	} // end get();
	
	/**
	 * Magic function so that $obj->key = "value" will work
	 *		 		
	 * @param string $key
	 * @param mixed $value		 		
	 * @return true
	 */	
	final public function __set($key, $value)
	{
		return $this->set($key, $value);
	} // end __set();
	
	/**
	 * @param string $key
	 * @param mixed $value
	 * @return true		 
	 */	
	public function set($key, $value)
	{
		switch($key)
		{
			case 'offset':
				if($value >= $this->get('all'))
				{
					throw new Opc_Exception('Page not found [offset: '.$value.']');
				}
				
				$this->$key = $value;
				$this->page = $this->offset / $this->get('limit') + 1;
				break;
			case 'page':				 
				if($value < 1 || $value > $this->get('pageCount'))
				{
					throw new Opc_Exception('Page not found ['.$value.']');
				}
				
				$this->$key = (int)$value;
				$this->offset = $this->get('limit') * ($this->page - 1);
				break;
			case 'decorator':
				if(is_string($value))
				{
					$decoratorClass = Opc_Paginator::getDecoratorClassName($value);
					
					if(!$decoratorClass)
					{
						throw new Opc_Exception('Undefined decorator ['.$value.']');
					}
					
					$this->_decorator = new $decoratorClass;
					
					if(!is_subclass_of($this->_decorator, 'Opc_Paginator_Decorator'))
					{
						throw new Opc_Exception('Given decorator ['.get_class($value).'] is not a subclass of "Opc_Paginator_Decorator".');
					}
					
					$this->_decorator->setPaginator($this);
				}
				elseif(is_object($value))
				{
					if(!is_subclass_of($value, 'Opc_Paginator_Decorator'))
					{
						throw new Opc_Exception('Given decorator ['.get_class($value).'] is not a subclass of "Opc_Paginator_Decorator".');
					}
					
					$this->_decorator = clone $value;
					$this->_decorator->setPaginator($this);
				}
				else
				{
					throw new Opc_Exception('Undefined decorator ['.$value.']');
				}
				
				$this->state(self::STATE_DIRTY);
				break;
			case 'all':
				$this->$key = (int)$value;
				break;
			case 'limit':
				$this->$key = (int)$value;
				
				if($this->$key < 1)
				{
					throw new Opc_OptionInvalid_Exception($key, get_class($this), 'grater than 0');
				}
				break;
			case 'page_float':
			case 'first':
			case 'last':
			case 'previous':
			case 'next':
				throw new Opc_OptionReadOnly_Exception($key, get_class($this));
				break;
			default:
				throw new Opc_OptionNotExists_Exception($key, get_class($this));
				break;
		}
		
		$this->state(self::STATE_DIRTY);
		return true;
	} // end set();
	
	/**
	 * @return void
	 */	
	public function setup()
	{
		$this->get('pageCount');
		$this->get('page');
		
		$this->get('decorator')->setup();
	} // end setup();
	
	// Iterator interface
	/**
	 * @return array
	 */	
	public function current()
	{						  
		$current = $this->get('decorator')->current();
		
		if(isset($current['number']))
		{
			$current['offset'] = Opc_Paginator::countOffset($current['number'], $this->get('limit'));
		}
		
		return $current;
	} // end current();

	/**
	 * @return integer
	 */	
	public function key()
	{		   
		return $this->_i;
	} // end key();

	/**
	 * @return void
	 */	
	public function next()
	{	  
		$this->_i++;
	} // end next();

	/**
	 * @return true
	 */	
	public function rewind()
	{		 
		$this->_i = 1;
		
		if($this->_state == Opc_Paginator_Range::STATE_DIRTY)
		{
			$this->setup();
			$this->state(Opc_Paginator_Range::STATE_CLEAN);
			
		}
		
		return true;
	} // end rewind();

	/**
	 * @return boolean
	 */	
	public function valid()
	{			
		return $this->_i <= $this->pageCount;
	} // end valid();
	
	// SeekableIterator interface
	/**
	 * @param integer $index
	 * @return void
	 */	
	public function seek($index)
	{		
		$this->_i = 1;
		$position = 1;

		while($position < $index && $this->valid())
		{
			$this->next();
			$position++;
		}

		if(!$this->valid())
		{
			throw new OutOfBoundsException('');
		}
	} // end seek();
	
	// Countable interface
	/**
	 * Returns the amount of all pages.
	 * Implements Countable interface so that count($pager) will work		 
	 * 				 
	 * @return integer
	 */	
	public function count()
	{
		return $this->pageCount;
	} // end count();
} // end Opc_Paginator_Range;
