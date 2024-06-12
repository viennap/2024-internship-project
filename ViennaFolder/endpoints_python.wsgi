import json

def application(environ, start_response):
    status = '200 OK'
    output = b'Hello World!'
    # output = bytes(str(environ), encoding = 'utf-8')
    
    path = environ.get('PATH_INFO', '').lstrip('/')

    if path == 'get_vehicle_trajectory.php':
        time = [1701389314.4, 1701389316.4, 1701389318.4, 1701389320.4, 1701389322.4, 1701389324.4, 1701389326.4, 1701389328.4, 1701389330.4, 1701389332.4, 1701389334.4, 1701389336.4, 1701389338.4, 1701389340.4, 1701389342.4, 1701389344.4, 1701389346.4, 1701389348.5, 1701389350.5, 1701389352.5, 1701389354.5, 1701389356.5, 1701389358.5, 1701389360.5, 1701389362.5, 1701389364.5, 1701389366.5, 1701389368.5, 1701389370.5, 1701389372.5, 1701389374.5, 1701389376.5, 1701389378.5, 1701389380.6, 1701389382.6, 1701389384.6, 1701389386.6, 1701389388.6, 1701389390.6, 1701389392.6, 1701389394.6, 1701389396.6, 1701389398.6, 1701389400.6, 1701389402.6, 1701389404.6, 1701389406.6, 1701389408.6, 1701389410.6, 1701389412.6, 1701389414.6, 1701389416.6, 1701389418.6, 1701389420.6, 1701389422.6, 1701389424.6, 1701389426.6, 1701389428.6, 1701389430.6, 1701389432.6, 1701389434.6, 1701389436.6, 1701389438.6, 1701389440.6, 1701389442.6, 1701389444.6, 1701389446.6, 1701389448.6, 1701389450.6, 1701389452.6, 1701389454.6, 1701389456.6, 1701389458.6, 1701389460.6, 1701389462.6, 1701389464.6, 1701389466.6, 1701389468.6, 1701389470.6, 1701389472.6, 1701389474.6, 1701389476.6, 1701389478.6, 1701389480.6, 1701389482.6, 1701389484.6, 1701389486.6, 1701389488.6, 1701389490.6, 1701389492.6, 1701389494.6, 1701389496.6]
        latitude = [36.368492126464844, 36.36836242675781, 36.36848449707031, 36.36856460571289, 36.36863327026367, 36.36870193481445, 36.3687629699707, 36.36880874633789, 36.36886215209961, 36.36892318725586, 36.36897659301758, 36.369022369384766, 36.36906433105469, 36.36909103393555, 36.369117736816406, 36.36916732788086, 36.36924743652344, 36.369380950927734, 36.36953353881836, 36.369693756103516, 36.36988830566406, 36.370094299316406, 36.37030029296875, 36.37050247192383, 36.37071228027344, 36.37092208862305, 36.37113952636719, 36.37135314941406, 36.371578216552734, 36.37180709838867, 36.37203598022461, 36.372257232666016, 36.372474670410156, 36.37271499633789, 36.372955322265625, 36.373172760009766, 36.373390197753906, 36.373600006103516, 36.37379455566406, 36.373985290527344, 36.37415313720703, 36.37430953979492, 36.374454498291016, 36.37458419799805, 36.37468338012695, 36.3747673034668, 36.37487030029297, 36.374961853027344, 36.375038146972656, 36.37511444091797, 36.37516784667969, 36.375205993652344, 36.37528991699219, 36.3753776550293, 36.3754997253418, 36.37564468383789, 36.37578582763672, 36.37590408325195, 36.37599563598633, 36.376102447509766, 36.376220703125, 36.37635040283203, 36.376502990722656, 36.37666320800781, 36.376834869384766, 36.37701416015625, 36.377197265625, 36.377384185791016, 36.3775634765625, 36.37773895263672, 36.377925872802734, 36.37812042236328, 36.3783073425293, 36.37848663330078, 36.37866973876953, 36.378849029541016, 36.37903594970703, 36.37922668457031, 36.37941360473633, 36.379600524902344, 36.3797607421875, 36.3798713684082, 36.37993621826172, 36.37997055053711, 36.37995910644531, 36.379947662353516, 36.37993240356445, 36.37992858886719, 36.37991714477539, 36.37990951538086, 36.37990188598633, 36.37989807128906]
        longitude = [-87.04999542236328, -87.04996490478516, -87.04985809326172, -87.04975128173828, -87.04965209960938, -87.0495376586914, -87.04940032958984, -87.04925537109375, -87.0490951538086, -87.0489273071289, -87.04877471923828, -87.04862976074219, -87.04850769042969, -87.04840850830078, -87.04835510253906, -87.04833984375, -87.04838562011719, -87.04850006103516, -87.04867553710938, -87.0488510131836, -87.0490493774414, -87.0492935180664, -87.0495376586914, -87.04975891113281, -87.04998016357422, -87.05021667480469, -87.05046844482422, -87.05072021484375, -87.05097961425781, -87.0512466430664, -87.051513671875, -87.0517807006836, -87.0520248413086, -87.05229949951172, -87.05255889892578, -87.05280303955078, -87.05306243896484, -87.0533218383789, -87.05360412597656, -87.05388641357422, -87.05418395996094, -87.05448913574219, -87.0547866821289, -87.0550765991211, -87.05537414550781, -87.05569458007812, -87.0560302734375, -87.05635070800781, -87.056640625, -87.05689239501953, -87.0571060180664, -87.0572509765625, -87.05728912353516, -87.0572509765625, -87.05719757080078, -87.05712890625, -87.05704498291016, -87.05693054199219, -87.05680847167969, -87.05673217773438, -87.05670928955078, -87.05670928955078, -87.05670928955078, -87.05669403076172, -87.05667877197266, -87.0566635131836, -87.05664825439453, -87.056640625, -87.05662536621094, -87.05661010742188, -87.05659484863281, -87.05657958984375, -87.05656433105469, -87.05655670166016, -87.05654907226562, -87.05653381347656, -87.0565185546875, -87.05650329589844, -87.0564956665039, -87.05648803710938, -87.0564956665039, -87.0564956665039, -87.05651092529297, -87.05652618408203, -87.0565185546875, -87.0564956665039, -87.05648040771484, -87.05647277832031, -87.05643463134766, -87.0563735961914, -87.05632019042969, -87.05628967285156]
  
        result = {
            "time": time,
            "latitude": latitude,
            "longitude": longitude
        }

        # output = b'Vehicle Trajectory!'
        output = json.dumps(result).encode('utf-8')

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