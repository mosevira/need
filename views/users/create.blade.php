<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
</head>
<body>
<h1>Create User</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>
        </div>
        <div>
            <label for="patronymic">Patronymic:</label>
            <input type="text" id="patronymic" name="patronymic">
        </div>
        <div>
             <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date">
        </div>
         <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">
        </div>
        <div>
            <label for="start_job_date">Start Job Date:</label>
             <input type="date" id="start_job_date" name="start_job_date">
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="role">Role:</label>
              <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="storekeeper">Storekeeper</option>
                <option value="seller">Seller</option>
            </select>
        </div>
          <div>
            <div>
                <label for="branch_id">Branch:</label>
                 <select name="branch_id" id="branch_id" required>
                    @foreach($branches as $branch)
                       <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                     @endforeach
                 </select>
             </div>
         </div>
         <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
         </div>
          <div>
            <label for="password_confirmation">Confirm password:</label>
           <input type="password" id="password_confirmation" name="password_confirmation" required>
         </div>
        <button type="submit">Create User</button>
    </form>
  <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
