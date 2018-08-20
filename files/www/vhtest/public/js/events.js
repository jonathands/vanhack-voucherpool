function loadVoucherGrid()
{
    let columnDefs = [
        {headerName: "Voucher Code", field: "code"},
        {headerName: "Date Created", field: "createdAt.date", filter: "date"},
        {headerName: "Used By", field: "usedBy"}
    ];

    let request = new XMLHttpRequest();
    let elVoucherList = document.querySelector('#voucher_list');

    request.open('GET', elVoucherList.dataset.endpoint);    
    request.onload = function() {
        if(request.status === 200) { 
            let gridOptions = {
                columnDefs: columnDefs,
                enableSorting: true,
                enableFilter: true,
                rowData: JSON.parse(request.responseText)
            };
        
            elVoucherList.innerHTML = "";
            new agGrid.Grid(elVoucherList, gridOptions);
        } else {
            alert(request.responseText);
        } 
    };
    request.send();
}


function processVoucher(event)
{
    event.preventDefault();

    let endpoint = this.getAttribute("action");
    let data = new FormData(this);
    post(endpoint, data, loadVoucherGrid);
    this.reset();
}

function post(endpoint,data,successCallback)
{
    request = new XMLHttpRequest();

    request.open('POST', endpoint);
    request.onload = function() {
        if (request.status === 200) {
            successCallback.call();
        }
        else if (request.status !== 200) {
            alert(request.responseText);
        }
    };
    request.send(data);
    
}

document.addEventListener("DOMContentLoaded", loadVoucherGrid);
document.querySelector("#form-use-voucher").addEventListener("submit",processVoucher);
document.querySelector("#form-save-voucher").addEventListener("submit",processVoucher);