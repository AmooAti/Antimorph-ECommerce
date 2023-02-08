<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $white_list_array = [
                'image/bmp', // 'bmp'
                'image/gif', // 'gif'
                'image/jpeg', // ['jpeg', 'jpg', 'jpe']
                'image/png', // 'png'
                'image/svg+xml', // ['svg', 'svgz']
                'image/tiff', // ['tiff', 'tif']
                'image/vnd.adobe.photoshop', // 'psd'
                'image/vnd.wap.wbmp', // 'wbmp'
                'image/webp', // 'webp'
                'image/x-icon', // 'ico'
            ];
            $white_list_string = str_replace(['[', ']'], ['(', ')'], json_encode($white_list_array));

            \Illuminate\Support\Facades\DB::statement('ALTER TABLE images MODIFY COLUMN `type` ENUM' . $white_list_string);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('type', 50)->change();
        });
    }
};
