import json
import os
import glob
from urllib.parse import urlparse, parse_qs
import pandas as pd
import strym
from strym import strymread
import vin_parser as vp

class VideoFile:
    def __init__(self, name, contents):
        self.name = name
        self.contents = contents

class CSVFile:
    def __init__(self, name, contents):
        self.name = name
        self.contents = contents

def get_vin(csvfile):
    # we use underscores to split up the filename
    splits = csvfile.split('_')
    candidates = []
    # print(f'The splits of the file are {splits}')
    for split in splits:
        # all VIN are 17 chars long
        if len(split) == 17:
            # keep them in an array, in case the path has other 17 char elements
            candidates.append(split)
    if len(candidates) >= 1:
        # return the end element, as none of our fileendings has 17 char elements at this time
        # HACK: if folks create _some17charfileending.csv then this will fail
        return candidates[-1]
    else:
        return 'VIN not part of filename'
    return csvfile

def generate_dbc_path(csv_path):
    vin = get_vin(csv_path)
    brand = "toyota"
    model = "rav4"
    year = "2019"
    try:
        if vp.check_valid(vin) == True:
            brand = vp.manuf(vin)
            brand = brand.split(" ")[0].lower()
            try:
                model = vp.online_parse(vin)['Model'].lower()
            except ConnectionError as e:
                print("Retrieving model of the vehicle requires internet connection. Check your connection.")
                return
            year = str(vp.year(vin))

    except:
        print('No valid vin... Continuing as Toyota RAV4. If this is inaccurate, please append VIN number to csvfile prefixed with an underscore.')

    inferred_dbc = "/var/www/html/ViennaFolder/endpoint_venv/lib/python3.8/site-packages/strym/dbc/{}_{}_{}.dbc".format(brand, model, year)
    return inferred_dbc

root_path = "/volume1/ViennaData/NonDashcamData/libpanda"

def get_vehicle_trajectory(args):
    # time = [1701389314.4, 1701389316.4, 1701389318.4, 1701389320.4, 1701389322.4, 1701389324.4, 1701389326.4, 1701389328.4, 1701389330.4, 1701389332.4, 1701389334.4, 1701389336.4, 1701389338.4, 1701389340.4, 1701389342.4, 1701389344.4, 1701389346.4, 1701389348.5, 1701389350.5, 1701389352.5, 1701389354.5, 1701389356.5, 1701389358.5, 1701389360.5, 1701389362.5, 1701389364.5, 1701389366.5, 1701389368.5, 1701389370.5, 1701389372.5, 1701389374.5, 1701389376.5, 1701389378.5, 1701389380.6, 1701389382.6, 1701389384.6, 1701389386.6, 1701389388.6, 1701389390.6, 1701389392.6, 1701389394.6, 1701389396.6, 1701389398.6, 1701389400.6, 1701389402.6, 1701389404.6, 1701389406.6, 1701389408.6, 1701389410.6, 1701389412.6, 1701389414.6, 1701389416.6, 1701389418.6, 1701389420.6, 1701389422.6, 1701389424.6, 1701389426.6, 1701389428.6, 1701389430.6, 1701389432.6, 1701389434.6, 1701389436.6, 1701389438.6, 1701389440.6, 1701389442.6, 1701389444.6, 1701389446.6, 1701389448.6, 1701389450.6, 1701389452.6, 1701389454.6, 1701389456.6, 1701389458.6, 1701389460.6, 1701389462.6, 1701389464.6, 1701389466.6, 1701389468.6, 1701389470.6, 1701389472.6, 1701389474.6, 1701389476.6, 1701389478.6, 1701389480.6, 1701389482.6, 1701389484.6, 1701389486.6, 1701389488.6, 1701389490.6, 1701389492.6, 1701389494.6, 1701389496.6]
    # latitude = [36.368492126464844, 36.36836242675781, 36.36848449707031, 36.36856460571289, 36.36863327026367, 36.36870193481445, 36.3687629699707, 36.36880874633789, 36.36886215209961, 36.36892318725586, 36.36897659301758, 36.369022369384766, 36.36906433105469, 36.36909103393555, 36.369117736816406, 36.36916732788086, 36.36924743652344, 36.369380950927734, 36.36953353881836, 36.369693756103516, 36.36988830566406, 36.370094299316406, 36.37030029296875, 36.37050247192383, 36.37071228027344, 36.37092208862305, 36.37113952636719, 36.37135314941406, 36.371578216552734, 36.37180709838867, 36.37203598022461, 36.372257232666016, 36.372474670410156, 36.37271499633789, 36.372955322265625, 36.373172760009766, 36.373390197753906, 36.373600006103516, 36.37379455566406, 36.373985290527344, 36.37415313720703, 36.37430953979492, 36.374454498291016, 36.37458419799805, 36.37468338012695, 36.3747673034668, 36.37487030029297, 36.374961853027344, 36.375038146972656, 36.37511444091797, 36.37516784667969, 36.375205993652344, 36.37528991699219, 36.3753776550293, 36.3754997253418, 36.37564468383789, 36.37578582763672, 36.37590408325195, 36.37599563598633, 36.376102447509766, 36.376220703125, 36.37635040283203, 36.376502990722656, 36.37666320800781, 36.376834869384766, 36.37701416015625, 36.377197265625, 36.377384185791016, 36.3775634765625, 36.37773895263672, 36.377925872802734, 36.37812042236328, 36.3783073425293, 36.37848663330078, 36.37866973876953, 36.378849029541016, 36.37903594970703, 36.37922668457031, 36.37941360473633, 36.379600524902344, 36.3797607421875, 36.3798713684082, 36.37993621826172, 36.37997055053711, 36.37995910644531, 36.379947662353516, 36.37993240356445, 36.37992858886719, 36.37991714477539, 36.37990951538086, 36.37990188598633, 36.37989807128906]
    # longitude = [-87.04999542236328, -87.04996490478516, -87.04985809326172, -87.04975128173828, -87.04965209960938, -87.0495376586914, -87.04940032958984, -87.04925537109375, -87.0490951538086, -87.0489273071289, -87.04877471923828, -87.04862976074219, -87.04850769042969, -87.04840850830078, -87.04835510253906, -87.04833984375, -87.04838562011719, -87.04850006103516, -87.04867553710938, -87.0488510131836, -87.0490493774414, -87.0492935180664, -87.0495376586914, -87.04975891113281, -87.04998016357422, -87.05021667480469, -87.05046844482422, -87.05072021484375, -87.05097961425781, -87.0512466430664, -87.051513671875, -87.0517807006836, -87.0520248413086, -87.05229949951172, -87.05255889892578, -87.05280303955078, -87.05306243896484, -87.0533218383789, -87.05360412597656, -87.05388641357422, -87.05418395996094, -87.05448913574219, -87.0547866821289, -87.0550765991211, -87.05537414550781, -87.05569458007812, -87.0560302734375, -87.05635070800781, -87.056640625, -87.05689239501953, -87.0571060180664, -87.0572509765625, -87.05728912353516, -87.0572509765625, -87.05719757080078, -87.05712890625, -87.05704498291016, -87.05693054199219, -87.05680847167969, -87.05673217773438, -87.05670928955078, -87.05670928955078, -87.05670928955078, -87.05669403076172, -87.05667877197266, -87.0566635131836, -87.05664825439453, -87.056640625, -87.05662536621094, -87.05661010742188, -87.05659484863281, -87.05657958984375, -87.05656433105469, -87.05655670166016, -87.05654907226562, -87.05653381347656, -87.0565185546875, -87.05650329589844, -87.0564956665039, -87.05648803710938, -87.0564956665039, -87.0564956665039, -87.05651092529297, -87.05652618408203, -87.0565185546875, -87.0564956665039, -87.05648040771484, -87.05647277832031, -87.05643463134766, -87.0563735961914, -87.05632019042969, -87.05628967285156]
    
    directories = os.listdir(root_path)
    trajectory_id = args["trajectory_id"][0]
    result = {}
    if trajectory_id in directories:
        full_folder_path = os.path.join(root_path, trajectory_id)
        glob_search = os.path.join(full_folder_path, "*_GPS_Messages.csv")
        variable_glob_results = glob.glob(glob_search)

        if len(variable_glob_results) > 0:
            df = pd.read_csv(variable_glob_results[0])
            df = df[df['Status'] == 'A']

            result = {
                "id": trajectory_id,
                "time": df['Systime'].to_list(),
                "latitude": df['Lat'].to_list(),
                "longitude": df['Long'].to_list()
            }
        else:
            result = {"error": "folder existed but no GPS file exists"}
    else:
        result = {"error": "folder does not exist"}

    return result

def get_vehicle_signal(args):
    trajectory_id = args["trajectory_id"][0]
    full_folder_path = os.path.join(root_path, trajectory_id)
    glob_search = os.path.join(full_folder_path, "*_CAN_Messages.csv")
    variable_glob_results = glob.glob(glob_search)
    if len(variable_glob_results) == 0:
        result = {"error" : "file does not exist"}
        return result
    
    r = strymread(csvfile = variable_glob_results[0], dbcfile=generate_dbc_path(variable_glob_results[0]))
        
    # time_speed = [1701389314.333874, 1701389316.330543, 1701389318.331073, 1701389320.333315, 1701389322.330893, 1701389324.330345, 1701389326.331383, 1701389328.33033, 1701389330.330672, 1701389332.333329, 1701389334.331528, 1701389336.331134, 1701389338.331494, 1701389340.331244, 1701389342.329876, 1701389344.331, 1701389346.331778, 1701389348.329675, 1701389350.329441, 1701389352.33114, 1701389354.329204, 1701389356.336824, 1701389358.331271, 1701389360.330447, 1701389362.330709, 1701389364.330177, 1701389366.328819, 1701389368.329246, 1701389370.328743, 1701389372.329097, 1701389374.329531, 1701389376.330457, 1701389378.330179, 1701389380.330864, 1701389382.33007, 1701389384.330514, 1701389386.330317, 1701389388.329472, 1701389390.330113, 1701389392.329445, 1701389394.330265, 1701389396.329443, 1701389398.329187, 1701389400.329373, 1701389402.329646, 1701389404.328629, 1701389406.329424, 1701389408.328639, 1701389410.328867, 1701389412.328451, 1701389414.328528, 1701389416.329102, 1701389418.328163, 1701389420.328495, 1701389422.329196, 1701389424.328595, 1701389426.329997, 1701389428.328251, 1701389430.327962, 1701389432.327843, 1701389434.327549, 1701389436.327277, 1701389438.335036, 1701389440.327297, 1701389442.327921, 1701389444.327303, 1701389446.327062, 1701389448.327995, 1701389450.328215, 1701389452.327395, 1701389454.32763, 1701389456.326926, 1701389458.327221, 1701389460.326899, 1701389462.326734, 1701389464.32668, 1701389466.326798, 1701389468.327809, 1701389470.326684, 1701389472.327814, 1701389474.327067, 1701389476.327449, 1701389478.327681, 1701389480.327299, 1701389482.327582, 1701389484.327537, 1701389486.327229, 1701389488.327665, 1701389490.327728, 1701389492.328101, 1701389494.326639, 1701389496.328876, 1701389498.326709, 1701389500.32665, 1701389502.327375]
    # average_speed = [16.46, 19.66, 20.59, 18.73, 18.7625, 21.922500000000003, 24.37, 26.3775, 28.0625, 28.1125, 25.252499999999998, 22.7175, 20.4525, 11.855, 9.5, 15.6525, 25.7875, 37.745000000000005, 46.477500000000006, 52.4075, 54.940000000000005, 55.8175, 55.1775, 53.9675, 53.352500000000006, 53.192499999999995, 54.042500000000004, 55.615, 56.09, 56.4375, 56.3025, 56.89, 56.395, 56.515, 56.61, 56.9375, 57.0625, 57.1225, 57.330000000000005, 57.627500000000005, 57.612500000000004, 56.5175, 54.4025, 51.315, 51.06750000000001, 55.5675, 55.74250000000001, 51.175000000000004, 45.6625, 39.830000000000005, 29.134999999999998, 18.865000000000002, 17.2825, 23.637500000000003, 29.165000000000003, 32.31, 31.540000000000003, 28.405, 25.727500000000003, 24.352500000000003, 25.707500000000003, 27.875000000000004, 30.325000000000003, 32.925, 34.61, 35.5675, 35.995, 35.9375, 34.935, 35.865, 37.4675, 37.6775, 35.845, 35.665, 36.1075, 36.4575, 37.06, 37.917500000000004, 36.605000000000004, 33.785000000000004, 28.2975, 16.92, 10.02, 0.0, 3.9225000000000003, 6.0925, 4.654999999999999, 3.1500000000000004, 7.55, 8.100000000000001, 5.2875, 3.9675000000000002, 3.95, 2.5949999999999998, 2.7025]
    # time_steer = [1701389314.322654, 1701389315.322122, 1701389316.322084, 1701389317.322757, 1701389318.322319, 1701389319.321811, 1701389320.323865, 1701389321.323933, 1701389322.321998, 1701389323.32771, 1701389324.322651, 1701389325.32305, 1701389326.323029, 1701389327.322428, 1701389328.32315, 1701389329.322929, 1701389330.322561, 1701389331.322461, 1701389332.329315, 1701389333.323198, 1701389334.322671, 1701389335.323436, 1701389336.323543, 1701389337.323001, 1701389338.323582, 1701389339.32361, 1701389340.323114, 1701389341.323055, 1701389342.323171, 1701389343.323986, 1701389344.323523, 1701389345.323593, 1701389346.3236, 1701389347.324035, 1701389348.323694, 1701389349.323305, 1701389350.32352, 1701389351.324592, 1701389352.323714, 1701389353.323835, 1701389354.32367, 1701389355.323816, 1701389356.336824, 1701389357.324151, 1701389358.323862, 1701389359.32377, 1701389360.323897, 1701389361.324044, 1701389362.324018, 1701389363.324855, 1701389364.324231, 1701389365.324198, 1701389366.324224, 1701389367.327624, 1701389368.326371, 1701389369.324841, 1701389370.32446, 1701389371.324634, 1701389372.324514, 1701389373.324664, 1701389374.324623, 1701389375.325249, 1701389376.325025, 1701389377.326401, 1701389378.325234, 1701389379.325808, 1701389380.330864, 1701389381.325397, 1701389382.324799, 1701389383.326158, 1701389384.325171, 1701389385.325618, 1701389386.325398, 1701389387.325385, 1701389388.325431, 1701389389.325673, 1701389390.325812, 1701389391.329235, 1701389392.32554, 1701389393.326193, 1701389394.325779, 1701389395.325865, 1701389396.326154, 1701389397.325879, 1701389398.325869, 1701389399.32627, 1701389400.326183, 1701389401.326023, 1701389402.32593, 1701389403.325837, 1701389404.325996, 1701389405.326787, 1701389406.326739, 1701389407.326068, 1701389408.326784, 1701389409.32636, 1701389410.326889, 1701389411.326455, 1701389412.326432, 1701389413.326544, 1701389414.327985, 1701389415.326742, 1701389416.327414, 1701389417.32741, 1701389418.326678, 1701389419.328304, 1701389420.327353, 1701389421.32731, 1701389422.327987, 1701389423.327405, 1701389424.326973, 1701389425.327286, 1701389426.329997, 1701389427.340409, 1701389428.327234, 1701389429.329444, 1701389430.32754, 1701389431.32719, 1701389432.327524, 1701389433.328089, 1701389434.327549, 1701389435.327819, 1701389436.327479, 1701389437.327671, 1701389438.335036, 1701389439.32784, 1701389440.328092, 1701389441.32794, 1701389442.327921, 1701389443.32777, 1701389444.328156, 1701389445.328142, 1701389446.328236, 1701389447.328185, 1701389448.328268, 1701389449.330172, 1701389450.328215, 1701389451.328741, 1701389452.329034, 1701389453.328207, 1701389454.328472, 1701389455.328707, 1701389456.328865, 1701389457.328956, 1701389458.329096, 1701389459.328591, 1701389460.328739, 1701389461.328641, 1701389462.328606, 1701389463.32881, 1701389464.328905, 1701389465.32921, 1701389466.328931, 1701389467.329937, 1701389468.329528, 1701389469.330388, 1701389470.329263, 1701389471.329013, 1701389472.329016, 1701389473.331238, 1701389474.329788, 1701389475.331501, 1701389476.330029, 1701389477.329512, 1701389478.330217, 1701389479.329685, 1701389480.329835, 1701389481.329593, 1701389482.331571, 1701389483.330062, 1701389484.330441, 1701389485.329739, 1701389486.329842, 1701389487.329787, 1701389488.329802, 1701389489.329967, 1701389490.330036, 1701389491.330133, 1701389492.3309, 1701389493.329977, 1701389494.330625, 1701389495.33034, 1701389496.332203, 1701389497.331315, 1701389498.330466, 1701389499.331385, 1701389500.331335, 1701389501.330698, 1701389502.330729]
    # message_steer = [-9.8, -28.3, -39.7, -52.1, -60.1, -47.400000000000006, -20.1, -14.9, -8.200000000000001, -1.3, 4.0, 2.5, -9.3, -9.3, -1.5, 2.3000000000000003, 0.1, 0.1, 1.1, 0.5, 0.30000000000000004, -0.1, -1.9000000000000001, -9.3, -18.6, 13.9, 99.4, 245.4, 403.70000000000005, 433.40000000000003, 274.3, 107.0, 38.400000000000006, 8.5, 12.100000000000001, 9.3, 4.3, 4.3, 3.9000000000000004, 6.300000000000001, 5.5, 5.4, 5.1000000000000005, 4.800000000000001, 3.7, 5.2, 5.5, 5.4, 4.4, 4.3, 0.6000000000000001, 0.6000000000000001, 2.1, 3.3000000000000003, 4.800000000000001, 4.6000000000000005, 4.4, 2.4000000000000004, 2.1, 2.7, 3.1, 4.0, 5.300000000000001, 3.6, 2.0, 1.9000000000000001, 3.2, 4.0, 4.3, 3.8000000000000003, 5.4, 5.5, 6.6000000000000005, 6.4, 9.700000000000001, 10.5, 11.4, 7.4, 9.5, 10.0, 11.8, 10.200000000000001, 9.8, 6.5, 3.5, 10.200000000000001, 13.700000000000001, 19.700000000000003, 13.200000000000001, 12.5, 4.800000000000001, 4.0, 3.7, 3.1, 3.5, 2.7, 2.3000000000000003, 3.1, 11.4, 9.600000000000001, -6.5, -74.3, -207.9, -254.8, -252.0, -76.4, -14.100000000000001, 1.4000000000000001, 5.7, 3.7, -8.3, -25.8, -41.400000000000006, -49.5, -50.0, -15.700000000000001, 65.60000000000001, 108.7, 117.2, 90.30000000000001, 27.1, -5.4, -9.600000000000001, -3.7, 5.5, 1.2000000000000002, -0.6000000000000001, 0.5, 5.9, 6.800000000000001, 5.7, 3.6, 3.2, 3.1, 8.3, 5.4, 1.9000000000000001, 1.9000000000000001, 6.800000000000001, 6.5, 4.800000000000001, 4.3, 4.2, 4.7, 4.4, 4.2, 4.3, 3.7, 1.8, 1.8, 5.4, 3.1, 3.1, 2.4000000000000004, 7.5, 6.800000000000001, 3.9000000000000004, 5.2, 8.5, 8.700000000000001, -2.8000000000000003, -14.3, 32.0, 162.0, 241.3, 171.5, 40.6, -206.60000000000002, -404.20000000000005, -496.90000000000003, -494.8, -303.0, 42.6, 70.0, 42.400000000000006, 42.300000000000004, -79.80000000000001, -118.10000000000001, -20.5, 259.1, 430.3, 438.5, 459.90000000000003, 566.1, 551.6, 419.3, 308.6, 180.10000000000002, 213.5]

    result = {}
    signal = args["signal_name"][0] # the first (and only) value of signal_name key is either "steer" or "speed"
    result_signal = None

    if signal == 'steer':
        result_signal = r.steer_angle()
    elif signal == 'speed':
        result_signal = r.speed()
    else:
        return {"error" : "Invalid signal name!"}
    result_signal = strymread.resample(result_signal, rate=1)
    
    result = {
        "time": result_signal['Time'].to_list(),
        "signal": result_signal['Message'].to_list()
    }
    return result

def get_trajectory_lists(args):
    start_time = float(args["start_time"][0])
    end_time = float(args["end_time"][0])

    if start_time > end_time :
        return {"error": "start_time: " + start_time + "is greater than end_time: " + end_time}
    
    bottom_left_lat = float(args["bottom_left_lat"][0])
    bottom_left_long = float(args["bottom_left_long"][0])

    top_right_lat = float(args["top_right_lat"][0])
    top_right_long = float(args["top_right_long"][0])

    directories = os.listdir(root_path)

    result = {"trajectories": {}, "rejected_trajectories": {}}

    for dir in directories:
        if dir not in (".", ".."):
            trajectory_id = dir
      
            can_file_glob_path = os.path.join(os.path.join(root_path, trajectory_id), "*_CAN_Messages.csv")
            gps_file_glob_path = os.path.join(os.path.join(root_path, trajectory_id), "*_GPS_Messages.csv")

            can_file = glob.glob(can_file_glob_path)
            gps_file = glob.glob(gps_file_glob_path)

            if len(can_file) > 0 and len(gps_file) > 0:
                gps_file = gps_file[0]
                can_file = can_file[0]
                
                gps_df = pd.read_csv(gps_file)
                gps_df_filtered = gps_df.loc[gps_df['Status'] == 'A'] 

                first_time = 0
                last_time = 0

                if len(gps_df_filtered) >= 2:
                    first_time = float(gps_df_filtered['Gpstime'].iloc[0])
                    last_time = float(gps_df_filtered['Gpstime'].iloc[-1])
                else:
                    first_time = float(gps_df['Systime'].iloc[0])
                    last_time = float(gps_df['Systime'].iloc[-1])
                
                first_lat = float(gps_df['Lat'].iloc[0])
                last_lat = float(gps_df['Lat'].iloc[-1])

                first_long = float(gps_df['Long'].iloc[0])
                last_long = float(gps_df['Long'].iloc[-1])

                # can_df = pd.read_csv(can_file)
                # speed = can_df['Speed'].tolist() if 'Speed' in can_df.columns else None
                # steering_angle = can_df['SteeringAngle'].tolist() if 'SteeringAngle' in can_df.columns else None

                new_trajectory = { 
                    "id": trajectory_id,
                    "requested_time_range": [start_time, end_time],
                    "requested_gps_range": [{"Longitude": bottom_left_long, "Latitude": bottom_left_lat}, {"Longitude": top_right_long, "Latitude": top_right_lat}],
                    "time_range": [first_time, last_time],
                    "gps_range": [{"Longitude": first_long, "Latitude": first_lat}, {"Longitude": last_long, "Latitude": last_lat}]
                }

                if valid_longitude(first_long, last_long, bottom_left_long, top_right_long) and valid_latitude(first_lat, last_lat, bottom_left_lat, top_right_lat):
                    if first_time >= start_time and last_time <= end_time:
                        result["trajectories"][trajectory_id] = new_trajectory
                    else:
                        result["rejected_trajectories"][trajectory_id] = new_trajectory
                        result["rejected_trajectories"][trajectory_id]["reason"] = "invalid time"
                else:
                    result["rejected_trajectories"][trajectory_id] = new_trajectory 
                    result["rejected_trajectories"][trajectory_id]["reason"] = "invalid coordinates"         
    return result

def valid_longitude(first_long, last_long, bottom_left_long, top_right_long):
    smaller_long = bottom_left_long 
    bigger_long = top_right_long 
    if smaller_long > bigger_long:
        temp = smaller_long 
        smaller_long = bigger_long
        bigger_long = temp
    if first_long >= smaller_long and first_long <= bigger_long and last_long >= smaller_long and last_long <= bigger_long:
        return True
    return False

def valid_latitude(first_lat, last_lat, bottom_left_lat, top_right_lat):
    smaller_lat = bottom_left_lat
    bigger_lat = top_right_lat
    if smaller_lat > bigger_lat:
        temp = smaller_lat 
        smaller_lat = bigger_lat
        bigger_lat = temp
    
    if first_lat >= smaller_lat and first_lat <= bigger_lat and last_lat >= smaller_lat and last_lat <= bigger_lat:
        return True
    return False

def get_vehicle_can(args):
    directories = os.listdir(root_path)
    trajectory_id = args["trajectory_id"][0]
    result = {}
    if trajectory_id in directories:
        full_folder_path = os.path.join(root_path, trajectory_id)
        glob_search = os.path.join(full_folder_path, "*_CAN_Messages.csv")
        variable_glob_results = glob.glob(glob_search)

        if len(variable_glob_results) > 0:
            basename = os.path.basename(variable_glob_results[0])
            contents = open(variable_glob_results[0], 'rb').read()
            return CSVFile(basename, contents)

def get_vehicle_gps(args):
    directories = os.listdir(root_path)
    trajectory_id = args["trajectory_id"][0]
    result = {}
    if trajectory_id in directories:
        full_folder_path = os.path.join(root_path, trajectory_id)
        glob_search = os.path.join(full_folder_path, "*_GPS_Messages.csv")
        variable_glob_results = glob.glob(glob_search)

        if len(variable_glob_results) > 0:
            basename = os.path.basename(variable_glob_results[0])
            contents = open(variable_glob_results[0], 'rb').read()
            return CSVFile(basename, contents)

dispatch_table = {}
dispatch_table["/get_vehicle_trajectory"] = get_vehicle_trajectory
dispatch_table["/get_vehicle_signal"] = get_vehicle_signal
dispatch_table["/get_trajectory_lists"] = get_trajectory_lists
dispatch_table["/get_vehicle_can"] = get_vehicle_can
dispatch_table["/get_vehicle_gps"] = get_vehicle_gps

def application(environ, start_response):
    endpoint = environ["PATH_INFO"]
    query_string = environ["QUERY_STRING"]
    query_string_dictionary = parse_qs(query_string)

    if endpoint in dispatch_table:
        status = '200 OK'
        handler_output = dispatch_table[endpoint](query_string_dictionary)
        response_headers = None
        if isinstance(handler_output, dict):
            handler_output = json.dumps(handler_output).encode('utf-8')
            response_headers = [('Content-type', 'application/json'),
                                ('Content-Length', str(len(handler_output)))]
        elif isinstance(handler_output, CSVFile):
            name = handler_output.name
            handler_output = handler_output.contents
            response_headers = [('Content-type', 'text/csv'),
                                ('Content-Disposition', 'attachment; filename="{}"'.format(name)),
                                ('Content-Length', str(len(handler_output)))]
        start_response(status, response_headers)
    else:
        status = '404 Not Found'

        handler_output = b'Endpoint not found!'
        response_headers = [('Content-type', 'text/plain'),
                            ('Content-Length', str(len(handler_output)))]
        start_response(status, response_headers)

    return [handler_output]