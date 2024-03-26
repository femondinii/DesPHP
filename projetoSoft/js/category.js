const saveCategory = async () =>{ 
    try {
        let name = document.querySelector("#category").value;
        let tax = document.querySelector("#tax").value;
        let data = {
            "name": name,
            "tax": tax
        }
        
        await fetch("http://localhost/8080?action=1", {
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
        const data = await fetch("http://localhost/8080?action=2");
        const res = await data.text();
        tbody.innerHTML = res;
    } catch (error) {
        console.log("error" + error.message);
    }
}

const deleteCategory = async (code) => {
    try {
        let cod = {
            "code": code
        }
    
        await fetch("http://localhost/8080?action=4",{
            method: "DELETE",
            body: JSON.stringify(cod)
        })
            then(res => res.json())
            .then(cod => {
                console.log(cod);
            });
    } catch (error) {
        console.log("error" + error.message);
    }
    updateTable();
}

document.addEventListener("DOMContentLoaded", updateTable);
