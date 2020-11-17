<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m201114_135603_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('books', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'desc' => $this->text(),
            'photo' => $this->string(),
            'publication' => $this->integer(),
            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('books');
    }
}
