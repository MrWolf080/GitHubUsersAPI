<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
</head>
<body>
<h1>Пользователи GitHub</h1>
<table id="users-table">
    <thead>
    <tr>
        <th>Аватар</th>
        <th>Логин</th>
        <th>Тип</th>
        <th>Профиль</th>
    </tr>
    </thead>
    <tbody id="users-table-body"></tbody>
</table>
<button id="prev-link">Previous</button>
<button id="next-link">Next</button>
</body>
<script>
    document.getElementById("prev-link").addEventListener("click", loadPrevPage);
    document.getElementById("next-link").addEventListener("click", loadNextPage);
    var per_page = 10;
    var first_id=0;
    var last_id=0;
    loadPage("/next");
    function loadPrevPage() {
        document.getElementById("users-table-body").innerHTML='';
        loadPage("/prev");
    }

    function loadNextPage() {
        document.getElementById("users-table-body").innerHTML='';
        loadPage("/next");
    }

    function loadPage(route)
    {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", route);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify({first_id: first_id, per_page: per_page, last_id:last_id}));
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if(data.length===0)
                {
                    var none = document.createElement("h5")
                    none.innerHTML = "Нет данных";
                    document.getElementById("users-table-body").appendChild(none);
                    last_id=0;
                    return;
                }
                data.sort(function(a, b) {
                    return a.id - b.id;
                });
                first_id = data[0]['id'];
                last_id = data[data.length-1]['id'];
                data.forEach(function(user) {
                    var row = document.createElement("tr");
                    var avatarCell = document.createElement("td");
                    var loginCell = document.createElement("td");
                    var typeCell = document.createElement("td");
                    var urlCell = document.createElement("td");

                    avatarCell.innerHTML = "<img src='" + user.avatar_url + "' width='120' height='120'>";
                    loginCell.innerHTML = user.login;
                    typeCell.innerHTML = user.type;
                    urlCell.innerHTML = "<a href='" + user.html_url + "'>Профиль</a>";

                    row.appendChild(avatarCell);
                    row.appendChild(loginCell);
                    row.appendChild(typeCell);
                    row.appendChild(urlCell);

                    document.getElementById("users-table-body").appendChild(row);
                });
            } else {
                console.error(xhr.statusText);
            }
        };
    }
</script>
</html>
