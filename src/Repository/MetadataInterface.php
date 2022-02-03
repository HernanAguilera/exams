<?php 

namespace App\Repository;

interface MetadataInterface {

    public static function getRelations(): array;
    public function completeFields($object): void;
}