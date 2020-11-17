<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books_authors}}`.
 */
class m201114_135844_create_books_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('books_authors', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
        ]);

        $this->addForeignKey('books_author_book_fk', 'books_authors', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('books_author_author_fk', 'books_authors', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('books_author_book_fk', 'books_authors');
        $this->dropForeignKey('books_author_author_fk', 'books_authors');

        $this->dropTable('books_authors');
    }
}
