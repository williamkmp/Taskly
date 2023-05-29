<?php

namespace App\Logic;

use App\Models\User;

class UserLogic
{
    /**
     * register new user to the database
     *
     * @param string $name
     * @param string $password
     * @param string $username
     * @param string $image_path
     *
     * @return User CreatedUSer
     */
	public function insert(string $username, string $email, string $password, string $image_path = null) {
        $newUser = new User;
        $newUser->name = $username;
        $newUser->email = $email;
        $newUser->password = bcrypt($password);
        $newUser->image_path = $image_path;
        $newUser->save();
        return $newUser;
	}
}
