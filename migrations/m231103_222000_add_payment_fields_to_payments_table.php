<?php
use yii\db\Migration;

/**
 * Class m231103_222000_add_payment_fields_to_payments_table
 */
class m231103_222000_add_payment_fields_to_payments_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('payments', 'payment_id', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('payments', 'payment_id');
    }
}
