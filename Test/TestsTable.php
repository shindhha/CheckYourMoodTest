<?php
require_once 'Modeles/Table.php';
require_once 'Test/TableTest.php';
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
class TestsTable extends TestCase
{

    public function testFillOnValidData() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest']
        $tableTest = new TableTest();
        // WHEN On appele la methode fill avec des valeurs dont les clés sont reconnues
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs valides']);
        // THEN La table enregistre les valeurs sous forme d'attributs.
        assertEquals('Fill sur des valeurs valides',$tableTest->nomTest);
    }

    public function testFillOnUnidentifiedData() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest']
        $tableTest = new TableTest();
        // WHEN On appele la methode fill avec des valeurs dont les clés ne sont pas reconnues
        $tableTest->fill(['age' => '12']);
        // THEN Les valeurs ne sont pas enregistrer.
        self::assertNull($tableTest->age);
    }

    public function testFill2TimeNullValues() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest'] avec des valeurs non nulle.
        $tableTest = new TableTest();
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs valides']);
        // WHEN On appele la methode fill avec un enssemble vide
        $tableTest->fill([]);
        // THEN Touts les attributs sont a nouveau null
        assertEquals(null,$tableTest->nomTest);
    }
    public function testFill2Time() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest'] avec des valeurs non nulle.
        $tableTest = new TableTest();
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs valides']);
        // WHEN On appele la methode fill avec un enssemble vide
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs déjà initialiser']);
        // THEN Touts les attributs ont leur nouvelle valeur.
        assertEquals("Fill sur des valeurs déjà initialiser",$tableTest->nomTest);
    }

    public function testFillWithouOverride2TimeNullValues() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest'] avec des valeurs non nulle.
        $tableTest = new TableTest();
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs valides']);
        // WHEN On appele la methode fill avec un enssemble vide
        $tableTest->fillWithoutOverride([]);
        // THEN Les attributs sont inchangé.
        assertEquals("Fill sur des valeurs valides",$tableTest->nomTest);
    }

    public function testFillWithouOverride2Time() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest'] avec des valeurs non nulle.
        $tableTest = new TableTest();
        $tableTest->fill(['nomTest' => 'Fill sur des valeurs valides']);
        // WHEN On appele la methode fill avec un enssemble vide
        $tableTest->fillWithoutOverride(['nomTest' => 'FillWithoutOverride sur des valeurs déjà initialiser']);
        // THEN Les attributs sont inchangé.
        assertEquals("Fill sur des valeurs valides",$tableTest->nomTest);
    }

    public function testToArray() {
        // GIVEN Une table avec les colonnes ['nomTest','dateTest'] ainsi que de multiples attributs
        $tableTest = new TableTest();
        $tableTest->fill(['nomTest' => 'Test toArray']);                   // Valeur valide
        $description = "Test de la transformation d'un objet en tableau";
        $tableTest->description = $description;                            // Valeur valide
        $tableTest->fillWithoutOverride(['test' => 'test']);               // Valeur invalide
        $tableTest->age = 12;                                              // Valeur invalide
        // EXCEPTED La fonction toArray() ne renvoie que les attributs
        // dont la clé est stocker dans 'fillable' sous la forme d'un tableau 'cle' => 'valeur'
        assertEquals(['nomTest' => 'Test toArray','description' => $description],$tableTest->toArray());
    }
}