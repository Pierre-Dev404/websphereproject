<?php

use PHPUnit\Framework\TestCase;

class CategorieTest extends TestCase
{
    function testlilian() {
        $category = new Categorie();
        $this->assertSame(2, $category->lilian(1));        
    }
    function testread() {
        $category = new Categorie();
        $result=array(
            'id' => '1',
            0 => '1',
            'type' => 'TiTi',
            1 => 'TiTi'        
        );
        $this->assertSame($result, $category->read(1));        
    }    
}
