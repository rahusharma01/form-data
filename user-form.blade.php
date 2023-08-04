<!DOCTYPE html>
<html>
<head>
    <title>User Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
</head>
<body>

 
    <form id="userForm" method="post" enctype="multipart/form-data" style="text-align: center;margin-top:100px;">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <span id="nameError"></span>
        </div>
        <br/>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <span id="emailError"></span>
        </div><br/>
        <div>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone">
            <span id="phoneError"></span>
        </div><br/>
        <div>
            <label for="country">Country:</label>
            <select name="country" id="country-dropdown">
                <option value="">Select Country</option> 
                @foreach ($data as $val) 
                    <option value="{{$val->id}}">
                        {{$val->name}}
                    </option>
                @endforeach
            </select>
            <span id="countryError"></span>
        </div>
        <br/>
        <div>
            <label for="state">State:</label>
            <select id="state-dropdown" name="state" class="form-control">
            <option value="">Select State</option> 
            </select>
            <span id="stateError"></span>
        </div><br/>
        <div>
            <label for="city">City:</label>
            <select id="city-dropdown" name="city" class="form-control">
            <option value="">Select City</option> 
            </select>
            <span id="cityError"></span>
        </div><br/>
        <div>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" id="profile_image">
            <span id="profileImageError"></span>
        </div><br/>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
 
    <table border="1"  style="margin-left: 500px;margin-top:40px;">
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
                    <td>{{ $user->countries->name }}</td>
                    <td>{{ $user->states->name }}</td>
                    <td>{{ $user->cities->name }}</td>
                    <td>
                        @if ($user->profile_image)
                            <img src="{{ asset('public/images/' . $user->profile_image) }}" alt="{{ $user->name }}" height="50">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
 
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
<script>

    $(document).ready(function () { 

        $('#country-dropdown').on('change', function () {

            var idCountry = this.value;

            $("#state-dropdown").html('');

            $.ajax({

                url: "{{url('/states')}}",

                type: "POST",

                data: {

                    country_id: idCountry,

                    _token: '{{csrf_token()}}'

                },

                dataType: 'json',

                success: function (result) {

                    $('#state-dropdown').html('<option value="">-- Select State --</option>');

                    $.each(result.states, function (key, value) {

                        $("#state-dropdown").append('<option value="' + value

                            .id + '">' + value.name + '</option>');

                    });

                    $('#city-dropdown').html('<option value="">-- Select City --</option>');

                }

            });

        });

 

        $('#state-dropdown').on('change', function () {

            var idState = this.value;

            $("#city-dropdown").html('');

            $.ajax({

                url: "{{url('/cities')}}",

                type: "POST",

                data: {

                    state_id: idState,

                    _token: '{{csrf_token()}}'

                },

                dataType: 'json',

                success: function (res) {

                    $('#city-dropdown').html('<option value="">-- Select City --</option>');

                    $.each(res.cities, function (key, value) {

                        $("#city-dropdown").append('<option value="' + value

                            .id + '">' + value.name + '</option>');

                    });

                }

            });

        });



    });

</script>
 
</body>
</html>