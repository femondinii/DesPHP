const showModal = (order_code) => {
    updateDetailsTable(order_code);
    const element = document.getElementById("modal");
    element.classList.add("show-modal");
}

const hideModal = () => {
    const element = document.getElementById("modal");
    element.classList.remove("show-modal");
}

document.addEventListener('DOMContentLoaded', () => {
    updateTable();
});

const updateTable = async () => {
    try{
        const tbodys = document.querySelector("tbody");
        const data = await fetch("http://localhost/8080?action=20");
        const res = await data.text();
        tbodys.innerHTML = res;
    } catch (error) {
        console.log("error" + error.message);
    }
}

const updateDetailsTable = async (order_code) => {
    const detail = document.getElementById("detail"); 
    let data = await fetch("http://localhost/8080?action=23", {
        method: "POST",
        body: JSON.stringify(order_code)
    })
    const res = await data.text();
    detail.innerHTML = res;
    
}
