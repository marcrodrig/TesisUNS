<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            # UserID,UserScreenName,UserName,UserCreatedAt, UserDescription, UserDescriptionLength,UserFollowersCount,
            # UserFriendsCount,UserLocation,AvgHashtag,AvgURLCount,AvgMention,AvgRetweet,
            # AvgFavCount,TweetCount,CurrentDate,bot
            $table->bigInteger('UserID');
            $table->string('UserScreenName');
            $table->string('UserName');
            $table->dateTime('UserCreatedAt');
            $table->string('UserDescription');
            $table->integer('UserDescriptionLength');
            $table->integer('UserFollowersCount');
            $table->integer('UserFriendsCount');
            $table->string('UserLocation')->nullable();
            $table->float('AvgHashtag');
            $table->float('AvgURLCount');
            $table->float('AvgMention');
            $table->float('AvgRetweet');
            $table->float('AvgFavCount');
            $table->integer('TweetCount');
            $table->dateTime('CurrentDate');
            $table->unsignedTinyInteger('bot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
