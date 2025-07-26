//Selección de filas de la tabla de restaurantes obteniendo el ID de cada empresa

let selectedRestaurantID = null;

function selectRows() {

    const rows = document.querySelectorAll(".background-table table tbody tr");

    rows.forEach(row => {
        row.addEventListener("click", function(event) {
            event.stopPropagation();
            rows.forEach(r => r.classList.remove("active-row"));
            this.classList.add("active-row");
            selectedRestaurantID = this.getAttribute('id');
            active_buttons(document.querySelector('.background-table table tbody'));
        });
    });

    document.addEventListener("click", function(event) {
        const table = document.querySelector(".background-table table tbody");
        const buttonEdit = document.querySelector('#buttonEdit');
        const overlayUpdateRestaurant = document.querySelector('#overlay-update-restaurant');
        const buttonErase = document.querySelector('#buttonErase');
        const overlayEraseRestaurant = document.querySelector('#overlay-erase-restaurant');
        if (!table.contains(event.target) && !buttonEdit.contains(event.target) && !overlayUpdateRestaurant.contains(event.target)
            && !buttonErase.contains(event.target) && !overlayEraseRestaurant.contains(event.target)) {
            rows.forEach(r => r.classList.remove("active-row"));
            active_buttons(document.querySelector('.background-table table tbody'));
        }
    });

}

selectRows();
active_buttons(document.querySelector('.background-table table tbody'));

//Función para desactivar botones dependiendo de si una empresa esta seleccionada o no

function active_buttons(table) {
    const select_row = table.querySelector('tr.active-row');
    const button1 = document.getElementById('buttonNew');
    const button2 = document.getElementById('buttonEdit');
    const button3 = document.getElementById('buttonErase');  
    if(select_row) {
        button1.disabled = true;
        button2.disabled = false;
        button3.disabled = false;
    } else {
        button1.disabled = false;
        button2.disabled = true;
        button3.disabled = true;
    }
}

//Abrir y cerrar el modal de nuevo restaurante

document.getElementById('buttonNew').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-new-restaurant').style.display = "block";
    document.getElementById('background-new-restaurant').style.display = "block";
})

document.getElementById('close-new-restaurant').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-new-restaurant').style.display = "none";
    document.getElementById('background-new-restaurant').style.display = "none";
    document.getElementById('name-new').style.backgroundColor = 'white';
    document.getElementById('name-new').style.border = '1px solid #0F4DBC';
    document.getElementById('name-new').value = "";
    document.getElementById('address-new').style.backgroundColor = 'white';
    document.getElementById('address-new').style.border = '1px solid #0F4DBC';
    document.getElementById('address-new').value = "";
    document.getElementById('phone-new').style.backgroundColor = 'white';
    document.getElementById('phone-new').style.border = '1px solid #0F4DBC';
    document.getElementById('phone-new').value = "";
})

//Abrir y cerrar el modal de actualizar restaurante

document.getElementById('buttonEdit').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-update-restaurant').style.display = "block";
    document.getElementById('background-update-restaurant').style.display = "block";

    //Mandamos el ID del usuario seleccionado al servidor y mostramos los datos devueltos en los input

    fetch('/showrestaurantdata', {
        method: 'POST',
        headers: {'Content-Type': 'application/json',},
        body: JSON.stringify({
            id: selectedRestaurantID
        })
    })
    .then(response => response.json())
    .then(data => {
            document.getElementById('name-update').value = data.name;
            document.getElementById('address-update').value = data.address;
            document.getElementById('phone-update').value = data.phone;
    })
})

document.getElementById('close-update-restaurant').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-update-restaurant').style.display = "none";
    document.getElementById('background-update-restaurant').style.display = "none";
    document.getElementById('name-update').value = "";
    document.getElementById('address-update').value = "";
    document.getElementById('phone-update').value = "";
})

//Abrir y cerrar el modal de eliminar restaurante

document.getElementById('buttonErase').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-erase-restaurant').style.display = "block";
    document.getElementById('background-erase-restaurant').style.display = "block";
})

document.getElementById('close-erase-restaurant').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('overlay-erase-restaurant').style.display = "none";
    document.getElementById('background-erase-restaurant').style.display = "none";
})

//Funcionalidad del modal Nuevo Restaurante

document.getElementById('save-new-restaurant').addEventListener('click', function() {
    
    //Capturamos los datos del nuevo usuario
    const name = document.getElementById('name-new').value;
    const address = document.getElementById('address-new').value;
    const phone = document.getElementById('phone-new').value;

    if (name && address && phone) {
        //Hacemos la petición al servidor
        fetch('newrestaurant', {
            method: 'POST',
            headers: {'Content-Type': 'application/json',},
            body: JSON.stringify({
                name: name,
                address: address,
                phone: phone
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('overlay-new-restaurant').style.display = 'none';
                document.getElementById('background-new-restaurant').style.display = 'none';
                location.reload();
            }
        })
    } else {
        if(!name) {
            document.getElementById('name-new').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('name-new').style.border = '2px solid red';
        }

        if(!address) {
            document.getElementById('address-new').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('address-new').style.border = '2px solid red';
        }

        if(!phone) {
            document.getElementById('phone-new').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('phone-new').style.border = '2px solid red';
        }
    }    
})

// Eliminación de alertas cuando empiezas a escribir en los input del modal nuevo restaurante

document.getElementById('name-new').addEventListener('keyup', function() {
    document.getElementById('name-new').style.backgroundColor = 'white';
    document.getElementById('name-new').style.border = '1px solid #0F4DBC';
});

document.getElementById('address-new').addEventListener('keyup', function() {
    document.getElementById('address-new').style.backgroundColor = 'white';
    document.getElementById('address-new').style.border = '1px solid #0F4DBC';
});

document.getElementById('phone-new').addEventListener('keyup', function() {
    document.getElementById('phone-new').style.backgroundColor = 'white';
    document.getElementById('phone-new').style.border = '1px solid #0F4DBC';
});

//Funcionalidad del modal Actualizar Restaurante

document.getElementById('save-update-restaurant').addEventListener('click', function() {
    
    //Capturamos los datos del nuevo usuario
    const name = document.getElementById('name-update').value;
    const address = document.getElementById('address-update').value;
    const phone = document.getElementById('phone-update').value;

    if (name && address && phone) {
        //Hacemos la petición al servidor
        fetch('updaterestaurant', {
            method: 'POST',
            headers: {'Content-Type': 'application/json',},
            body: JSON.stringify({
                name: name,
                address: address,
                phone: phone,
                id: selectedRestaurantID
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('overlay-update-restaurant').style.display = 'none';
                document.getElementById('background-update-restaurant').style.display = 'none';
                location.reload();
            }
        })
    } else {
        if(!name) {
            document.getElementById('name-update').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('name-update').style.border = '2px solid red';
        }

        if(!address) {
            document.getElementById('address-update').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('address-update').style.border = '2px solid red';
        }

        if(!phone) {
            document.getElementById('phone-update').style.backgroundColor = 'rgb(251, 203, 203)';
            document.getElementById('phone-update').style.border = '2px solid red';
        }
    }    
})

// Eliminación de alertas cuando empiezas a escribir en los input del modal actualizar restaurante

document.getElementById('name-update').addEventListener('keyup', function() {
    document.getElementById('name-update').style.backgroundColor = 'white';
    document.getElementById('name-update').style.border = '1px solid #0F4DBC';
});

document.getElementById('address-update').addEventListener('keyup', function() {
    document.getElementById('address-update').style.backgroundColor = 'white';
    document.getElementById('address-update').style.border = '1px solid #0F4DBC';
});

document.getElementById('phone-update').addEventListener('keyup', function() {
    document.getElementById('phone-update').style.backgroundColor = 'white';
    document.getElementById('phone-update').style.border = '1px solid #0F4DBC';
});

//Funcionalidad del modal eliminar restaurante

document.getElementById('erase-restaurant').addEventListener('click', function() {
    fetch('eraserestaurant', {
        method: 'POST',
        headers: {'Content-Type': 'application/json',},
        body: JSON.stringify({ id: selectedRestaurantID })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('overlay-erase-restaurant').style.display = "none";
            document.getElementById('background-erase-restaurant').style.display = "none";
            location.reload();
        }
    })
})