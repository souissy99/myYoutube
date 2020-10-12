# -*- coding: utf-8 -*-

from flask_restful import Resource, reqparse
from smtplib import SMTP

sender = "habi_a@etna-alternance.net"
message_template = ("""From: Açal Habi <{0}>
To: <{1}>
Subject: Notification MyYoutube

{2}""")
messages = {
    "password": "Password updated",
    "encoding": "Encoding finished"
}

class Mails(Resource):
    def post(self):
        parser = reqparse.RequestParser()
        parser.add_argument('receiver', required=True, help="Receiver cannot be blank!")
        parser.add_argument('type', required=True, help="Type cannot be blank!")
        args = parser.parse_args()
        receivers = [args['receiver']]
        result = {
            "sender": sender,
            "receiver": args['receiver'],
            "type": args['type']
        }
        smtp = SMTP("postfix")
        smtp.sendmail(sender, receivers, message_template.format(sender, args['receiver'], messages[args['type']]))
        smtp.quit()
        return result
