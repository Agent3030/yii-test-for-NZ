<?php

use yii\db\Migration;

/**
 * Class m181013_132020_categories
 */
class m181013_132020_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(256),
            'link' => $this->string(1024),
            'parent_id'=> $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181013_132020_categories cannot be reverted.\n";

        return false;
    }
    */
}
