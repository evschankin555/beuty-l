<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payments}}`.
 */
class m231013_224012_create_payments_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('payments', [
            'id' => $this->primaryKey(),
            'card_number' => $this->string(16)->notNull(),
            'email' => $this->string()->notNull(),
            'ticket_count' => $this->integer()->notNull(),
            'product_name' => $this->string()->notNull(),
            'datetime' => $this->dateTime()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('payments');
    }
}
