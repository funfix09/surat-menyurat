<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_mails', function (Blueprint $table) {
            $table->id();
            $table->string('referance_number')->comment('Nomor Surat');
            $table->date('date_letter_number')->comment('Tanggal Nomor Surat');
            $table->string('origin_number')->comment("Nomor Asal");
            $table->date('date_of_origin')->comment("Tanggal Nomor Asal");
            $table->string('sender_mail');
            $table->text('regarding')->comment("Perihal");
            $table->string('attachment_file')->comment("File Lampiran");
            $table->boolean('is_urgent')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('incoming_mails', function (Blueprint $table) {
            $table->foreignId('user_id')
                    ->nullable()
                    ->after('is_urgent')
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
        });

        Schema::create('incoming_mail_divisions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('incoming_mail_divisions', function (Blueprint $table) {
            $table->foreignId('mail_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('incoming_mails')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                    
            $table->foreignId('division_id')
                    ->nullable()
                    ->after('mail_id')
                    ->constrained('divisions')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incoming_mails', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('incoming_mail_divisions', function (Blueprint $table) {
            $table->dropForeign(['mail_id']);
            $table->dropColumn('mail_id');
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });
        Schema::dropIfExists('incoming_mails');
        Schema::dropIfExists('incoming_mail_divisions');
    }
}
