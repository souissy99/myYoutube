from flask import Flask
from flask_restful import Resource, Api

import sys

sys.path.insert(0, './routes')
from mails import *

app = Flask(__name__)
api = Api(app)


class Home(Resource):
    def get(self):
        return {'message':'Hello!'}

# Home
api.add_resource(Home, '/')

# Route POST for sending mails
api.add_resource(Mails, '/mail')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
