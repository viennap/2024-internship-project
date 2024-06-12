def application(environ, start_response):
    status = '200 OK'
    output = b'Hello World!'
    output = bytes(str(environ), encoding = 'utf-8')

    input = environ.get('PATH_INFO')
    print(input);

    response_headers = [('Content-type', 'text/plain'),
                        ('Content-Length', str(len(output)))]
    start_response(status, response_headers)

    return [output]