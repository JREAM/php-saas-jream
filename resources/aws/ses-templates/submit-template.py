import subprocess
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("sq", help="Integer to square", type=int)
parser.add_argument("-v", "--verbose", help="Verbose", action="store_true")
args = parser.parse_args()
print(args.sq**2)
if args.verbose:
    print('verbose on')

result = subprocess.run(['ls', '-lta'])
result.stdout
#result = subprocess.run("aws ses send-bulk-templated-email --cli-input-json file://mybulkemail.json", stdout=subprocess.PIPE)
# result.stdout.decode('utf-8')

