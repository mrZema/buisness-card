export default class RequestService {

    static error_500_message = '<span class="error">Server Error</span>';

    //Requests to Server
    static apiRequest(callback, api_command_id, data) {
        let getRequestParams = '/api-command/request?id='+api_command_id;
        this.requestToServer(callback, getRequestParams, data);
    }

    static requestToServer(callback, getRequestParams, data) {
        let http = new XMLHttpRequest();
        let url = location.protocol+'//'+location.hostname+getRequestParams;
        http.open('POST', url, true);
        http.setRequestHeader('X-CSRF-Token', yii.getCsrfToken());
        http.responseType = 'json';
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4) {
                callback(http.response);
            }
        };
        http.send(JSON.stringify(data));
    }

    //Request parsing
    static findGetParameter(parameterName) {
        let result = null,
            tmp = [];
        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
                tmp = item.split("=");
                if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }
}
