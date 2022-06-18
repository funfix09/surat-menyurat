<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_mails', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->nullable();
            $table->date('date_letter_number')->nullable();
            $table->text('regarding');
            $table->text('receiver');
            $table->string('attachment_file');
            $table->boolean('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->foreignId('division_id')
                    ->nullable()
                    ->after('receiver')
                    ->constrained('divisions')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            $table->foreignId('user_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });
        Schema::dropIfExists('outgoing_mails');
    }
}
