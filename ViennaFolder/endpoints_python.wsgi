def application(environ, start_response):
    status = '200 OK'
    output = b'Hello World!'
    # output = bytes(str(environ), encoding = 'utf-8')

    path = environ.get('PATH_INFO', '').lstrip('/')

    if path == 'get_vehicle_trajectory':
        status = '200 OK'
        response = {"message": "Vehicle trajectory data"}
        output = b'Vehicle Trajectory!'
        content_type = 'application/json'
    elif path == 'get_vehicle_signal':
        status = '200 OK'
        response = {"message": "Vehicle signal data"}
        output = b'Vehicle Signal!'
        content_type = 'application/json'
    elif path == 'get_trajectory_lists.php':
        status = '200 OK'
        trajectory_files = [f for f in os.listdir('.') if f.startswith('trajectory_')]
        response = {"trajectory_files": trajectory_files}
        output = b'Trajectory lists!'
        content_type = 'application/json'
    else:
        status = '404 Not Found'
        output = b'Endpoint not found'
        content_type = 'text/plain'

    response_headers = [('Content-type', 'text/plain'),
                        ('Content-Length', str(len(output)))]
    start_response(status, response_headers)

    return [output]