let host = window.location.host.split(':')[0];
let socket = io.connect('//' + host + ':8000', {rejectUnauthorized: false});

socket.on('connect', function () {
    console.log('CONNECT');

    socket.on('validator', function (data) {
        console.log('VALIDATOR EVENT', data);

        switch (data.action) {
            case 'CREDIT':
                Livewire.emit('creditAdded');
                break;

            case 'PROXY':
                switch (data.data.event) {
                    case 'orderFinished':
                        console.log('orderFinished');
                        setTimeout(function(){
                            window.location.replace(data.data.url);
                        }, 3000);
                        document.querySelector('#NotifyOrderFinished').autoplay = true;
                        break;
                    case 'orderPaid':
                        console.log('orderPaid');
                        Livewire.emit('orderPaid');
                        break;
                    case 'creditAdded':
                        console.log('creditAdded');
                        Livewire.emit('creditAdded');
                        break;
                    case 'orderCreated':
                        console.log('orderCreated');
                        Livewire.emit('orderCreated');
                        break;
                    case 'maintenanceMode':
                        console.log('maintenanceMode');
                        setTimeout(function(){
                            window.location.replace('http://' + host);
                        }, 1000);
                        break;
                }
                break;

        }
    });

    socket.on('disconnect', function () {
        console.log('disconnect')
    })
});

$("button.modal-button").click(function (e) {
    e.preventDefault();
    let modal_id = $(this).data('modal-id');
    $(modal_id).modal();
});