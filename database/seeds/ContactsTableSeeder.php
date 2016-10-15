<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $i = 0;
        $limit = 200;
        for ($i; $i < $limit; $i++) {
            $sex = (array_rand(['f', 'm']) == 0) ? 'f' : 'm';
            DB::table('contacts')->insert([
                'id' => $faker->unique()->uuid,
                'name' => ($sex == 'f') ? $faker->firstNameFemale : $faker->firstNameMale,
                'sex' => $sex,
                'birthday' => $faker->dateTimeThisDecade,
                'email' => $faker->unique()->freeEmail,
                'phone' => $faker->unique()->e164PhoneNumber,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'postcode' => $faker->postcode,
                'country' => $faker->country,
                //'photo' => url()->current() . '/images/cats/' . mt_rand(1, 10) . '.jpeg',
                'photo' => 'http://192.34.60.253' . '/images/cats/' . mt_rand(1, 10) . '.jpeg',
                'favorite' => $faker->boolean,
                'created_at' => $faker->dateTimeThisMonth,
                'updated_at' => $faker->dateTimeThisMonth,
            ]);
        }
    }
}
