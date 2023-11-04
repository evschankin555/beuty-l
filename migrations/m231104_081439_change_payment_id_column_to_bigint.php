<?php

use yii\db\Migration;

/**
 * Class m231104_081439_change_payment_id_column_to_bigint
 */
class m231104_081439_change_payment_id_column_to_bigint extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('payments', 'payment_id', $this->bigInteger());
    }

    public function safeDown()
    {
        $this->alterColumn('payments', 'payment_id', $this->integer());
    }

}
