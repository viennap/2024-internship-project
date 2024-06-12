import json
import os
import subprocess
from urllib.parse import parse_qs

def run_php_script(script_name, query_string):
    """Runs a PHP script with query parameters and returns its output."""
    env = os.environ.copy()
    env['QUERY_STRING'] = query_string

    try:
        result = subprocess.run(['php', script_name], capture_output=True, text=True, env=env, check=True)
        return result.stdout
    except subprocess.CalledProcessError as e:
        return json.dumps({"error": str(e)})
    
def application(environ, start_response):
    status = '200 OK'
    output = b'Hello World!'
    # output = bytes(str(environ), encoding = 'utf-8')
    
    path = environ.get('PATH_INFO', '').lstrip('/')
    query_string = environ.get('QUERY_STRING', '')
    
    if path == 'get_vehicle_trajectory.php':
        output = run_php_script('get_vehicle_trajectory.php', query_string).encode('utf-8')
        # output = b'Vehicle Trajectory!'
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