const saveProduct = async () => {
    try {
        let product = document.querySelector("#pname").value;
        let amount = document.querySelector("#pamount").value;
        let price = document.querySelector("#pprice").value;
        let category = document.querySelector("#pcategory").value;
        
        let data = {
            "name": product,
            "price": price,
            "amount": amount,
            "ccode": category
        }
        
        await fetch("http://localhost/8080?action=5", {
            method: "POST",
            body: JSON.stringify(data),
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
            });
    } catch (error) {
        console.log("error" + error.message);
    }
    updateTable();
}

const updateTable = async () => {
    try {
        const tbody = document.querySelector("tbody");
        const data = await fetch("http://localhost/8080?action=6");
        const res = await data.text();
        tbody.innerHTML = res;
    } catch (error) {
        console.log("error" + error.message);
    }
}

const deleteProducts = async (code_prod) => {
    try {
        let cod = {
            "code_prod": code_prod
        }
    
        await fetch("http://localhost/8080?action=8",{
            method: "DELETE",
            body: JSON.stringify(cod)
        })
            then(res => res.json())
            .then(cod => {
                console.log(cod);
            });
    } catch (error) {
        alert("Não pode excluir");
    }
    updateTable();
}

const populateSelect = async () => {
    try {
        const selectCategory = document.getElementById("pcategory");
        const data = await fetch("http://localhost/8080?action=7");
        const res = await data.json();
        console.log(res);

        var option = '<option value="">Category</option>';
        if(res['status']){
            for(let i = 0; i < res.data.length; i++){
                console.log(res.data[i]['code']);
                console.log(res.data[i]['name']);
               
                option += '<option value="' + res.data[i]['code'] + '">'+ res.data[i]['name'] +'</option>';
            }
            selectCategory.innerHTML = option;
        }
    } catch (error) {
        console.log("error" + error);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    populateSelect();
    updateTable();
});

