<!DOCTYPE html>
<html>
<head>
    <title>User Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
</head>
<body>


    <h1>User Form</h1>
    <form id="userForm" method="post" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <span id="nameError"></span>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <span id="emailError"></span>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone">
            <span id="phoneError"></span>
        </div>
        <div>
            <label for="country">Country:</label>
            <select name="country" id="country">
                <option value="">Select Country</option> 
            </select>
            <span id="countryError"></span>
        </div>
        <div>
            <label for="state">State:</label>
            <select name="state" id="state">
                <option value="">Select State</option>
            </select>
            <span id="stateError"></span>
        </div>
        <div>
            <label for="city">City:</label>
            <select name="city" id="city">
                <option value="">Select City</option> 
            </select>
            <span id="cityError"></span>
        </div>
        <div>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" id="profile_image">
            <span id="profileImageError"></span>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>

    <h2>Users Table</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Profile Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->country }}</td>
                    <td>{{ $user->state }}</td>
                    <td>{{ $user->city }}</td>
                    <td>
                        @if ($user->profile_image)
                            <img src="{{ asset('public/images/' . $user->profile_image) }}" alt="{{ $user->name }}" height="50">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/country-state-city/1.2.2/js/country-state-city.min.js"></script>
<script> 

    $(document).ready(function () { 
        let countries = Object.values(countryStateCity.getCountryList());
        countries.forEach((country) => {
            $('#country').append(`<option value="${country.isoCode}">${country.name}</option>`);
        });
 
        $('#country').change(function () {
            let countryCode = $(this).val();
            let states = Object.values(countryStateCity.getStatesByCountryCode(countryCode));
            $('#state').html('<option value="">Select State</option>');
            states.forEach((state) => {
                $('#state').append(`<option value="${state.isoCode}">${state.name}</option>`);
            });
            $('#city').html('<option value="">Select City</option>');
        });
 
        $('#state').change(function () {
            let countryCode = $('#country').val();
            let stateCode = $(this).val();
            let cities = Object.values(countryStateCity.getCitiesByCountryCodeAndStateCode(countryCode, stateCode));
            $('#city').html('<option value="">Select City</option>');
            cities.forEach((city) => {
                $('#city').append(`<option value="${city.name}">${city.name}</option>`);
            });
        });
 
    });
</script>
    <script> 
        $(document).ready(function () {
            $('#country').select2();
            $('#state').select2();
            $('#city').select2();

            $('#userForm').submit(function (event) {
                event.preventDefault();

                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('users.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        alert(response.message);
                        $('#userForm')[0].reset();
                        loadUsersTable();
                    },
                    error: function (xhr, status, error) {
                        if (xhr.responseJSON) {
                            displayErrors(xhr.responseJSON.errors);
                        }
                    },
                });
            });
 
            function loadUsersTable() {
                $.ajax({
                    url: "{{ route('users.index') }}",
                    method: "GET",
                    success: function (response) {
                        $('tbody').html(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }
 
            function displayErrors(errors) {
                Object.keys(errors).forEach(function (key) {
                    $('#' + key + 'Error').text(errors[key][0]);
                });
            }
        });
    </script>

</body>
</html>





