<?php

    class Brand
    {
        private $brand_name;
        private $id;

        function __construct($brand_name, $id=null)
        {
            $this->brand_name = $brand_name;
            $this->id = $id;
        }

        function setBrandName($new_brand_name)
        {
            $this->brand_name = (string) $new_brand_name;
        }

        function getBrandName()
        {
            return $this->brand_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO brands (brand_name) VALUES ('{$this->getBrandName()}');");
            $this->id=$GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_brand = $GLOBALS['DB']->query("SELECT * FROM brands");
            $brands = array();
            foreach($returned_brand as $brand){
                $brand_name = $brand['brand_name'];
                $id = $brand['id'];
                $new_brand = new Brand($brand_name, $id);
                array_push($brands, $new_brand);
            }
            return $brands;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands");
        }

        static function find($search_id)
        {
            $found_brand = null;
            $brands = Brand::getAll();

            foreach ($brands as $brand)
            {
                $brand_id = $brand->getId();
                if ($brand_id == $search_id)
                {
                    $found_brand = $brand;
                }
            }
            return $found_brand;
        }

        function addStore($store)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$this->getId()}, {$store->getId()});");
        }

        function getStores()
        {
            $stores = $GLOBALS['DB']->query("SELECT stores.* FROM brands
            JOIN brands_stores ON (brands.id = brands_stores.brand_id)
            JOIN stores ON (brands_stores.store_id = stores.id)
            WHERE brands.id = {$this->getId()};");

            $result_stores = array();
            foreach ($stores as $store)
            {
                $store_name = $store['store_name'];
                $id = $store['id'];
                $new_store = new Store($store_name, $id);
                array_push($result_stores, $new_store);
            }
            return $result_stores;
        }
    }




?>
