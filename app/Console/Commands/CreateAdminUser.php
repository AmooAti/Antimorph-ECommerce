<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {email} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Admin User';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        #Initilize Email and Password
        $email = $this->argument('email');
        $password = $this->argument('password')?$this->argument('password'):Str::random(16);

        #check if emails are Unique
        if(Admin::where('email' , $email)->first()){
            $this->info("Email should be Unique, Admin with $email email ,Already Exists");
            return Command::FAILURE;
        }

        #Generating new record for admin database
        $admin = new Admin();
        $admin->email = $email;
        $admin->password = bcrypt($password);

        #check if Saving operation succeed
        if(!$admin->save()){
            return Command::FAILURE;
        }

        #demonstrate credential information
        $this->info("Admin User Created :\nEmail:$email\nPassword:$password");
        return Command::SUCCESS;
    }
}
