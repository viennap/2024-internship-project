import pandas as pd
from sqlalchemy import create_engine

arg_dict = {'db_id': 'circles',
            'db_pw': 'wjytxeu5',
            'db_ip': 'localhost',
            'db_port': '3306',
            'db_name': 'circledb'}

def get_latest_target():
    """

        Call the latest target speed profile and max headway profile with:
            http://{host_ip}:{port}/inrix/api/target

        Example:
            curl -i http://localhost:5000/inrix/api/target

        Response:
            generated_at | int | UTC timestamp
            position | array | postmile range
            speed | array | target speed profile
            open_gap | array | max headway for ACC

    """
    db_id, db_pw, db_ip, db_port, db_name = \
        arg_dict['db_id'], arg_dict['db_pw'], arg_dict['db_ip'], arg_dict['db_port'], arg_dict['db_name']

    engine = create_engine(f'mysql+pymysql://{db_id}:{db_pw}@{db_ip}:{db_port}/{db_name}')
    sql_qry = 'select * from target_profile order by generated_at desc limit 1;'
    tp = pd.read_sql(sql_qry, engine)
    #print(tp)
    return tp.T.to_json()
    #return tp

#get_latest_target()
