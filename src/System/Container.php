<?php

namespace MN\System;

/**
 * Class Container
 *
 * @package MN\System
 */
class Container {

	/** @var array */
	protected $instances = [];
	
	/**
	 * @param $abstract
	 * @param null $concrete
	 */
	public function set($abstract, $concrete = null) {
		if(is_null($concrete))
			$concrete = $abstract;
		
		$this->instances[$abstract] = $concrete;
	}
	
	/**
	 * @param $abstract
	 * @param array $parameters
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function get($abstract, $parameters = []) {
		if(!isset($this->instances[$abstract]))
			$this->set($abstract);
		
		return $this->resolve($this->instances[$abstract], $parameters);
	}
	
	/**
	 * resolve single
	 *
	 * @param callable $concrete
	 * @param $parameters
	 *
	 * @return mixed|object
	 * @throws \Exception
	 */
	public function resolve($concrete, $parameters) {
		
		if($concrete instanceof \Closure)
			return $concrete($this, $parameters);
		
		$reflector = new \ReflectionClass($concrete);
		if(!$reflector->isInstantiable())
			throw new \Exception("Class {$concrete} is not instantiable");
		
		$constructor = $reflector->getConstructor();
		if(is_null($concrete))
			return $reflector->newInstance();
		
		$parameters = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters);
		
		return $reflector->newInstanceArgs($dependencies);
	}
	
	/**
	 * get all dependencies resolved
	 *
	 * @param $parameters
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getDependencies($parameters) {
		$dependencies = [];
		
		foreach($parameters AS $parameter) {
			$dependency = $parameter->getClass();
			
			if(is_null($dependency)) {
				if($parameter->isDefaultValueAvailable()) {
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new \Exception("Can not resolve class dependency {$parameter->name}");
				}
			} else {
				$dependencies[] = $this->get($dependency->name);
			}
		}
		
		return $dependencies;
	}
}