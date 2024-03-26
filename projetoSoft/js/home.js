const removeStock = async () => {
    try {
        await fetch("http://localhost/8080?action=15",{
            method: "GET"
        })
            then(res => res.json())
            .then(cod => {
                console.log(cod);
            });
    } catch (error) {
        console.log("error" + error.message);
    }
}

const saveCart = async () => {   
    try {
        let product = document.querySelector("#hproduct").value;
        let amount = parseInt(document.querySelector("#hamount").value);
        let tax =  parseInt(document.querySelector("#htax").value);
        let price =  parseFloat(document.querySelector("#hprice").value);
        let taxa = (amount * price) * (tax/100);
        let total = (amount * price) + taxa;
        
        let data = {
            "code": product,
            "amount": amount,
            "tax": tax,
            "price": price,
            "total": total,
            "totalTaxa": taxa
        }
        
        await fetch("http://localhost/8080?action=9", {
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
    
};

const populateSelect = async () => {
    try {
        const selectProd = document.querySelector("#hproduct");
        const data = await fetch("http://localhost/8080?action=11");
        const res = await data.json();

        var option = '<option value="">Products</option>';
        if(res['status']){
            for(let i = 0; i < res.data.length; i++){                
                option += '<option value="' + res.data[i]['code_prod'] + '">'+ res.data[i]['name_prod'] +'</option>';
            }
            selectProd.innerHTML = option;
        }
    } catch (error) {
        console.log("error" + error.message);
    }
}

const completeInput = async () =>{
    try {
        var selectProd = document.querySelector("#hproduct").value;
        const inputTax = document.getElementById("htax");
        const inputPrice= document.getElementById("hprice");
        const data = await fetch("http://localhost/8080?action=10");
        const res = await data.json();

        for(let i = 0; i < res.data.length; i++){
            if(selectProd == res.data[i]['code_prod']){
                inputTax.value = res.data[i]['tax'];
                inputPrice.value = res.data[i]['price'];
            }
        }
    } catch (error) {
        console.log("error" + error.message);
    }
}

document.querySelector("#hproduct").addEventListener("change", completeInput);


document.addEventListener("DOMContentLoaded", () => {
    populateSelect();
    updateTable();
});

const updateTable = async () => {
    try{
        const tbodys = document.querySelector("tbody");
        const total = document.getElementById("ttotal");
        const data = await fetch("http://localhost/8080?action=12");
        const res = await data.text();  
        const valueTbody = JSON.parse(res);
        total.innerHTML = valueTbody.form;
        tbodys.innerHTML = valueTbody.tbody;
    } catch (error) {
        console.log("error" + error.message);
    }
}

const deleteCart = async (id) => {
    try {
        let cod = {
            "id": id
        }
    
        await fetch("http://localhost/8080?action=13",{
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

document.getElementById("cancel").addEventListener("click", async () => {
    deleteAllCart();
});

const deleteAllCart = async () => {
    try {
        await fetch("http://localhost/8080?action=18",{
            method: "GET"
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

const setHistory = async () => {
    try {
        await fetch("http://localhost/8080?action=19",{
            method: "GET"
        })
            then(res => res.json())
            .then(cod => {
                console.log(cod);
            });
    } catch (error) {
        console.log("error" + error.message);
    }
    deleteAllCart();
}

const setDetails = async () => {
    try {
        await fetch("http://localhost/8080?action=22",{
            method: "GET"
        })
            then(res => res.json())
            .then(cod => {
                console.log(cod);
            });
    } catch (error) {
        console.log("error" + error.message);
    }
    deleteAllCart();
}

document.getElementById("finish").addEventListener("click", () => {
    setHistory();
    setDetails();
    removeStock();
});


