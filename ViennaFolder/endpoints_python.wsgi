def application(environ, start_response):
    status = '200 OK'
    output = b'Hello World!'
    # output = bytes(str(environ), encoding = 'utf-8')

    path = environ.get('PATH_INFO', '').lstrip('/')

    if path == 'get_vehicle_trajectory.php':
        output = b'Vehicle Trajectory!'
    elif path == 'get_vehicle_signal.php':
        output = b'Vehicle Signal!'
    elif path == 'get_trajectory_lists.php':
        output = b'Trajectory lists!'
    else:
        output = b'Invalid endpoint'

    response_headers = [('Content-type', 'text/plain'),
                        ('Content-Length', str(len(output)))]
    start_response(status, response_headers)

    return [output]